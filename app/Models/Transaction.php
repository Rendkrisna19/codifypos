<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    // Kolom apa saja yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'tenant_id',
        'package_id',
        'order_id',
        'amount',
        'snap_token',
        'status',
        'payment_type',
        'paid_at'
    ];

    // Beritahu Laravel kalau kolom paid_at itu format tanggal (datetime)
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * Relasi ke tabel tenants (Transaksi ini milik siapa?)
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relasi ke tabel packages (Transaksi ini beli paket apa?)
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}