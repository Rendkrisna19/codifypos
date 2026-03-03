<?php
namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;

class Pos extends Component
{
    public $search = '';
    public $selectedCategory = null; // null = Semua
    
    // State Keranjang & Transaksi
    public $cart = [];
    public $orderType = 'dine_in';
    public $paymentMethod = 'cash'; // Default cash, lainnya dikunci
    public $amountTendered = null;
    
    // Total Kalkulasi
    public $subtotal = 0;
    public $taxRate = 0.11; // PPN 11% (Dinamis nantinya)
    public $tax = 0;
    public $grandTotal = 0;
    public $change = 0;

    // State untuk Struk (Receipt)
    public $receiptData = null;

    public function setCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function addToCart($productId)
    {
        // Ambil data asli dari database
        $product = Product::where('is_active', true)->find($productId);
        
        if ($product) {
            $cartIndex = collect($this->cart)->search(fn($item) => $item['id'] === $productId);
            
            if ($cartIndex !== false) {
                $this->cart[$cartIndex]['qty']++;
            } else {
                $this->cart[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->selling_price,
                    'cost_price' => $product->cost_price, // Penting untuk laporan laba nanti
                    'qty' => 1,
                ];
            }
            $this->calculateTotals();
        }
    }

    public function increaseQty($index) {
        $this->cart[$index]['qty']++;
        $this->calculateTotals();
    }

    public function decreaseQty($index) {
        if ($this->cart[$index]['qty'] > 1) {
            $this->cart[$index]['qty']--;
        } else {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
        }
        $this->calculateTotals();
    }

    public function clearCart() {
        $this->cart = [];
        $this->amountTendered = null;
        $this->receiptData = null;
        $this->calculateTotals();
    }

    public function calculateTotals() {
        $this->subtotal = collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
        $this->tax = $this->subtotal * $this->taxRate; 
        $this->grandTotal = $this->subtotal + $this->tax;
        $this->calculateChange();
    }

    public function updatedAmountTendered() {
        $this->calculateChange();
    }

    public function calculateChange() {
        $tendered = (float) ($this->amountTendered ?: 0);
        $this->change = $tendered - $this->grandTotal;
    }

    public function processPayment()
    {
        if (empty($this->cart)) return;

        if ($this->amountTendered < $this->grandTotal) {
            $this->addError('payment', 'Uang tunai kurang dari total tagihan!');
            return;
        }

        // 1. Simpan ke tabel Orders
        $order = Order::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->id(),
            'order_number' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999),
            'order_type' => $this->orderType,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax,
            'grand_total' => $this->grandTotal,
            'amount_tendered' => $this->amountTendered,
            'change_amount' => $this->change,
            'payment_method' => $this->paymentMethod,
        ]);

        // 2. Simpan Item ke tabel Order_Items & Kurangi Stok
        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'unit_cost_price' => $item['cost_price'],
                'unit_selling_price' => $item['price'],
                'subtotal' => $item['price'] * $item['qty'],
            ]);

            // Kurangi Stok Produk Asli
            Product::where('id', $item['id'])->decrement('stock', $item['qty']);
        }

        // 3. Siapkan Data Struk (Receipt)
        $this->receiptData = [
            'cafe_name' => auth()->user()->tenant->name,
            'order_number' => $order->order_number,
            'date' => now()->format('d M Y H:i'),
            'cashier' => auth()->user()->name,
            'items' => $this->cart,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'grand_total' => $this->grandTotal,
            'tendered' => $this->amountTendered,
            'change' => $this->change,
            'order_type' => $this->orderType == 'dine_in' ? 'Dine In' : 'Take Away'
        ];

        // Kosongkan keranjang tapi biarkan modal struk terbuka
        $this->cart = [];
        $this->amountTendered = null;
        $this->calculateTotals();
    }

    public function render()
    {
        // Logika Filter & Search Real Database
        $query = Product::where('is_active', true);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        return view('livewire.pos', [
            'products' => $query->get(),
            'categories' => Category::all(),
        ])->layout('components.layouts.app');
    }
}