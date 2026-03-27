<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
        $table->foreignId('package_id')->constrained();
        $table->string('order_id')->unique(); // Untuk Midtrans (INV-...)
        $table->decimal('amount', 15, 2);
        $table->string('snap_token')->nullable();
        $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
        $table->string('payment_type')->nullable(); // qris, bank_transfer, dll
        $table->timestamp('paid_at')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
