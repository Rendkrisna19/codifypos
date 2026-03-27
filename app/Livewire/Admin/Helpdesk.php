<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Tenant;
use App\Models\HelpdeskMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection; // Tambahkan ini

#[Layout('layouts.admin')]
class Helpdesk extends Component
{
    public $tenants = [];
    public $activeTenantId = null;
    public $activeTenantName = '';
    
    // Hapus `= []` agar Livewire tidak memaksa ini menjadi Array statis
    public $messages; 
    public $newMessage = '';

    public function mount()
    {
        // Inisialisasi sebagai Collection kosong
        $this->messages = collect(); 
        $this->loadTenants();
    }

    public function loadTenants()
    {
        $this->tenants = Tenant::with(['helpdeskMessages' => function($query) {
            $query->latest()->limit(1);
        }])
        ->withCount(['helpdeskMessages as unread_count' => function($query) {
            $query->where('is_read_by_admin', false);
        }])
        ->orderByDesc('unread_count') 
        ->get();
    }

    public function selectTenant($tenantId, $tenantName)
    {
        $this->activeTenantId = $tenantId;
        $this->activeTenantName = $tenantName;
        
        HelpdeskMessage::where('tenant_id', $tenantId)
            ->where('is_read_by_admin', false)
            ->update(['is_read_by_admin' => true]);

        $this->loadMessages();
        $this->loadTenants(); 
    }

    public function loadMessages()
    {
        if ($this->activeTenantId) {
            $this->messages = HelpdeskMessage::with('sender')
                ->where('tenant_id', $this->activeTenantId)
                ->oldest()
                ->get();
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|max:1000'
        ]);

        HelpdeskMessage::create([
            'tenant_id' => $this->activeTenantId,
            'user_id' => Auth::id(),
            'message' => $this->newMessage,
            'is_read_by_admin' => true, 
            'is_read_by_tenant' => false, 
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        $this->loadTenants(); 
    }

    #[On('echo:helpdesk,MessageSent')]
    public function receiveRealtimeMessage($event)
    {
        $this->loadTenants();
        if ($this->activeTenantId == $event['tenant_id']) {
            $this->loadMessages();
            HelpdeskMessage::where('id', $event['message_id'])->update(['is_read_by_admin' => true]);
        }
    }

    public function render()
    {
        return view('livewire.admin.helpdesk');
    }
}