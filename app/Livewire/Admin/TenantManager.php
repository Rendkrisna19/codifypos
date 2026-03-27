<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\TrialReminderMail;

#[Layout('layouts.admin')] // Pastikan ini sesuai dengan path layout admin Anda
class TenantManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // --- FITUR BLOKIR TENANT ---
    public function toggleSuspend($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $tenant->is_active = !$tenant->is_active;
        $tenant->save();

        $status = $tenant->is_active ? 'diaktifkan kembali' : 'diblokir (suspend)';
        session()->flash('success', "Status Tenant {$tenant->name} berhasil {$status}.");
    }

    // --- FITUR HAPUS PERMANEN ---
    public function deleteTenant($tenantId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $name = $tenant->name;
        
        // Hapus tenant (Otomatis menghapus user, produk, kategori, dll karena ON DELETE CASCADE di SQL Anda)
        $tenant->delete();

        session()->flash('success', "Tenant {$name} dan seluruh datanya berhasil dihapus permanen.");
    }

    // --- FITUR NOTIFIKASI WA ---
    public function sendWhatsAppReminder($tenantId)
    {
        $tenant = Tenant::with(['users' => function($q) {
            $q->where('role', 'owner');
        }])->findOrFail($tenantId);

        $owner = $tenant->users->first();
        
        if (!$owner || empty($owner->phone)) {
            session()->flash('error', "Gagal! Nomor WhatsApp {$owner->name} tidak ditemukan.");
            return;
        }

        $now = Carbon::now()->startOfDay();
        $trialUntil = Carbon::parse($tenant->trial_until)->startOfDay();
        $daysLeft = (int) $now->diffInDays($trialUntil, false);

        if ($daysLeft < 0) {
            $message = "*[CODIFYPOS ALERT]*\n\nHalo {$owner->name}, masa aktif sistem untuk *{$tenant->name}* telah EXPIRED sejak " . abs($daysLeft) . " hari yang lalu.\n\nSistem kasir Anda saat ini ditangguhkan. Silakan login ke dashboard untuk memperpanjang langganan.";
        } else {
            $message = "*[CODIFYPOS ALERT]*\n\nHalo {$owner->name}, masa trial untuk *{$tenant->name}* akan segera berakhir dalam *{$daysLeft} hari*.\n\nMohon segera lakukan perpanjangan langganan agar kasir dapat terus beroperasi.";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])->post('https://api.fonnte.com/send', [
                'target' => $owner->phone,
                'message' => $message,
                'countryCode' => '62', 
            ]);

            if ($response->successful() && $response->json('status') == true) {
                session()->flash('success', "WhatsApp terkirim ke {$owner->name}");
            } else {
                session()->flash('error', 'Fonnte Error: ' . $response->json('reason', 'Gagal mengirim.'));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Koneksi API WA Gagal: ' . $e->getMessage());
        }
    }

    // --- FITUR NOTIFIKASI EMAIL ---
    public function sendEmailReminder($tenantId)
    {
        $tenant = Tenant::with(['users' => function($q) {
            $q->where('role', 'owner');
        }])->findOrFail($tenantId);

        $owner = $tenant->users->first();

        if (!$owner || empty($owner->email)) {
            session()->flash('error', 'Gagal! Email owner tidak ditemukan.');
            return;
        }

        $now = Carbon::now()->startOfDay();
        $trialUntil = Carbon::parse($tenant->trial_until)->startOfDay();
        $daysLeft = (int) $now->diffInDays($trialUntil, false);

        try {
            Mail::to($owner->email)->send(new TrialReminderMail($tenant, $owner, $daysLeft));
            session()->flash('success', "Email berhasil dikirim ke {$owner->email}");
        } catch (\Exception $e) {
            session()->flash('error', 'SMTP Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Tenant::with(['users' => function($q) {
            $q->where('role', 'owner');
        }])
        ->when($this->search, function($q) {
            $q->where('name', 'like', '%' . $this->search . '%');
        });

        $today = Carbon::now()->startOfDay();
        
        if ($this->filterStatus === 'expiring_soon') {
            $query->whereBetween('trial_until', [$today, $today->copy()->addDays(3)->endOfDay()]);
        } elseif ($this->filterStatus === 'expired') {
            $query->where('trial_until', '<', $today);
        } elseif ($this->filterStatus === 'active') {
            $query->where('trial_until', '>', $today->copy()->addDays(3)->endOfDay());
        }

        $tenants = $query->paginate(10);

        foreach ($tenants as $tenant) {
            $now = Carbon::now()->startOfDay();
            $trialUntil = Carbon::parse($tenant->trial_until)->startOfDay();
            $tenant->days_left = (int) $now->diffInDays($trialUntil, false);
        }

        return view('livewire.admin.tenant-manager', [
            'tenants' => $tenants,
            'stats' => [
                'total' => Tenant::count(),
                'expired' => Tenant::where('trial_until', '<', $today)->count(),
                'expiring_soon' => Tenant::whereBetween('trial_until', [$today, $today->copy()->addDays(3)->endOfDay()])->count(),
            ]
        ]);
    }
}