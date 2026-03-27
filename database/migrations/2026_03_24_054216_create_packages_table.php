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
    Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Contoh: Basic, Pro, Enterprise
        $table->integer('duration_days'); // Contoh: 30, 90, 365
        $table->decimal('price', 15, 2); // Harga
        $table->text('features')->nullable(); // Fitur (Bisa dipisah dengan koma/baris baru)
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
