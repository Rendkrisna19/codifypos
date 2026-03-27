<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\PrinterSetting;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentProcess extends Component
{
    use WithPagination;

    public $activeTab = 'unpaid'; // 'unpaid' atau 'paid'
    
    // Filter & Search
    public $search = '';
    public $filterDate = '';
    
    // Transaksi aktif
    public $selectedOrder = null;
    public $amountTendered = null;
    public $change = 0;
    
    // Data Cetak
    public $receiptData = null;

    // Reset pagination jika filter berubah
    public function updating($property)
    {
        if (in_array($property, ['search', 'filterDate', 'activeTab'])) {
            $this->resetPage();
        }
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = Order::with('items.product')->find($orderId);
        $this->amountTendered = null;
        $this->change = 0;
    }

    public function updatedAmountTendered()
    {
        if ($this->selectedOrder) {
            $tendered = (float) ($this->amountTendered ?: 0);
            $this->change = $tendered - $this->selectedOrder->grand_total;
        }
    }

    public function processPayment()
    {
        if (!$this->selectedOrder) return;
        if ($this->amountTendered < $this->selectedOrder->grand_total) {
            $this->addError('payment', 'Uang tunai kurang!'); return;
        }

        $this->selectedOrder->update([
            'amount_tendered' => $this->amountTendered,
            'change_amount' => $this->change,
            'payment_status' => 'paid',
            'user_id' => auth()->id()
        ]);

        $this->generateReceiptData($this->selectedOrder);
        $this->selectedOrder = null; 
    }

    // FITUR BARU: Cetak Ulang Struk
    public function reprint($orderId)
    {
        $order = Order::with('items.product')->find($orderId);
        if ($order) {
            $this->generateReceiptData($order);
        }
    }

    // Fungsi helper untuk menyusun data struk (Dipakai saat Bayar & Cetak Ulang)
    private function generateReceiptData($order)
    {
        $printer = PrinterSetting::where('tenant_id', auth()->user()->tenant_id)->first();
        
        $items = [];
        foreach($order->items as $item) {
            $items[] = [
                'name' => $item->product->name ?? 'Produk', 
                'qty' => $item->qty, 
                'price' => $item->unit_selling_price
            ];
        }

        $this->receiptData = [
            'cafe_name' => auth()->user()->tenant->name,
            'header' => $printer?->header_text ?? '',
            'footer' => $printer?->footer_text ?? 'Terima Kasih Atas Kunjungan Anda',
            'paper_size' => $printer?->paper_size ?? '58mm',
            'auto_print' => $printer?->auto_print ?? false,
            'order_number' => $order->order_number,
            'customer' => $order->customer_name,
            'date' => $order->created_at->format('d M Y H:i'), // Tanggal asli transaksi
            'cashier' => auth()->user()->name,
            'items' => $items,
            'subtotal' => $order->subtotal,
            'tax' => $order->tax_amount,
            'grand_total' => $order->grand_total,
            'tendered' => $order->amount_tendered,
            'change' => $order->change_amount,
            'order_type' => $order->order_type == 'dine_in' ? 'Dine In' : 'Take Away',
            'is_reprint' => true // Penanda jika ini cetak ulang
        ];
    }

    public function render()
    {
        // Query untuk Antrean (Belum Bayar) -> Tanpa Pagination agar kasir bisa lihat semua
        $unpaidOrders = Order::where('tenant_id', auth()->user()->tenant_id)
            ->where('payment_status', 'unpaid')
            ->when($this->search, function($q) {
                $q->where('customer_name', 'like', '%'.$this->search.'%')
                  ->orWhere('order_number', 'like', '%'.$this->search.'%');
            })
            ->oldest() 
            ->get();

        // Query untuk Riwayat (Sudah Lunas) -> Pakai Pagination
        $paidOrders = Order::with('cashier')->where('tenant_id', auth()->user()->tenant_id)
            ->where('payment_status', 'paid')
            ->when($this->search, function($q) {
                $q->where('customer_name', 'like', '%'.$this->search.'%')
                  ->orWhere('order_number', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterDate, function($q) {
                $q->whereDate('created_at', $this->filterDate);
            })
            ->latest() 
            ->paginate(10); // 10 data per halaman

        return view('livewire.payment-process', [
            'unpaidOrders' => $unpaidOrders,
            'paidOrders' => $paidOrders
        ])->layout('components.layouts.app');
    }
}