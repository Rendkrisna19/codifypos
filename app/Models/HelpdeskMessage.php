<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpdeskMessage extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'message',
        'is_read_by_admin',
        'is_read_by_tenant',
    ];

    // Relasi ke tabel Tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Relasi ke Pengirim (User)
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}