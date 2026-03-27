<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('helpdesk_messages', function (Blueprint $table) {
            $table->id();
            // Pesan ini milik ruang obrolan bisnis/tenant yang mana?
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            // Siapa yang mengirim pesan ini? (Bisa Superadmin, bisa Owner, bisa Staff)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->text('message');
            
            // Indikator pesan belum terbaca (Penting untuk fitur Badge Notifikasi angka merah)
            $table->boolean('is_read_by_admin')->default(false);
            $table->boolean('is_read_by_tenant')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('helpdesk_messages');
    }
};