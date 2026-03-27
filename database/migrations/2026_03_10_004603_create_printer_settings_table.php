<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('printer_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('paper_size')->default('58mm'); // 58mm atau 80mm
            $table->string('printer_name')->default('Printer Kasir 1');
            $table->string('header_text')->nullable();
            $table->string('footer_text')->default('Terima Kasih Atas Kunjungan Anda');
            $table->boolean('auto_print')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('printer_settings'); }
};