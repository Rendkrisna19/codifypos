<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // Kasir yang melayani
            $table->string('order_number')->unique();
            $table->string('order_type')->default('dine_in'); // dine_in, take_away
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2); // PPN
            $table->decimal('grand_total', 15, 2);
            $table->decimal('amount_tendered', 15, 2); // Uang dibayar
            $table->decimal('change_amount', 15, 2); // Kembalian
            $table->string('payment_method')->default('cash');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};