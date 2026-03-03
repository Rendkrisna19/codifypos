<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    // WAJIB ADA AGAR BISA DI-CREATE SAAT OTP BERHASIL
    protected $fillable = [
        'name',
        'slug',
        'trial_until',
        'is_active',
    ];

    // Opsional: Untuk memastikan trial_until dibaca sebagai format waktu
    protected function casts(): array
    {
        return [
            'trial_until' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}