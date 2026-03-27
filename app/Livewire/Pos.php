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
    public $selectedCategory = null;
    
    public $cart = [];
    public $orderType = 'dine_in';
    public $customerName = ''; 
    
    public $subtotal = 0;
    public $taxRate = 0.11;
    public $tax = 0;
    public $grandTotal = 0;

    public function setCategory($categoryId) { $this->selectedCategory = $categoryId; }

    public function addToCart($productId)
    {
        $product = Product::where('is_active', true)->find($productId);
        if ($product) {
            $cartIndex = collect($this->cart)->search(fn($item) => $item['id'] === $productId);
            if ($cartIndex !== false) {
                $this->cart[$cartIndex]['qty']++;
            } else {
                $this->cart[] = [
                    'id' => $product->id, 'name' => $product->name, 'price' => $product->selling_price,
                    'cost_price' => $product->cost_price, 'qty' => 1, 'image' => $product->image
                ];
            }
            $this->calculateTotals();
        }
    }

    public function increaseQty($index) { $this->cart[$index]['qty']++; $this->calculateTotals(); }
    public function decreaseQty($index) {
        if ($this->cart[$index]['qty'] > 1) { $this->cart[$index]['qty']--; } 
        else { unset($this->cart[$index]); $this->cart = array_values($this->cart); }
        $this->calculateTotals();
    }

    public function clearCart() {
        $this->cart = []; $this->customerName = ''; $this->calculateTotals();
    }

    public function calculateTotals() {
        $this->subtotal = collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
        $this->tax = $this->subtotal * $this->taxRate; 
        $this->grandTotal = $this->subtotal + $this->tax;
    }

    // FOKUS UTAMA: HANYA SIMPAN PESANAN
    public function submitOrder()
    {
        $this->validate(['customerName' => 'required|string|max:255'], ['customerName.required' => 'Nama/Meja wajib diisi!']);
        if (empty($this->cart)) return;

        $order = Order::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->id(), // Waiter yang menginput
            'order_number' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999),
            'customer_name' => $this->customerName,
            'order_type' => $this->orderType,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax,
            'grand_total' => $this->grandTotal,
            'amount_tendered' => 0,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'payment_status' => 'unpaid', // STATUS BELUM BAYAR
        ]);

        foreach ($this->cart as $item) {
            OrderItem::create([
                'order_id' => $order->id, 'product_id' => $item['id'], 'qty' => $item['qty'],
                'unit_cost_price' => $item['cost_price'], 'unit_selling_price' => $item['price'], 'subtotal' => $item['price'] * $item['qty'],
            ]);
            Product::where('id', $item['id'])->decrement('stock', $item['qty']);
        }

        session()->flash('success', 'Pesanan ' . $this->customerName . ' dikirim ke Dapur & Kasir!');
        $this->clearCart();
    }

    public function render()
    {
        $query = Product::where('is_active', true);
        if ($this->search) { $query->where('name', 'like', '%' . $this->search . '%'); }
        if ($this->selectedCategory) { $query->where('category_id', $this->selectedCategory); }

        return view('livewire.pos', [
            'products' => $query->get(),
            'categories' => Category::all(),
        ])->layout('components.layouts.app');
    }
}