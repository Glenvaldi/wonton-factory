<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Contoh: ORD-65123
            
            // Info Pelanggan
            $table->string('name');
            $table->string('phone');
            $table->text('address')->nullable(); // Boleh kosong jika Dine In/Takeaway
            
            // Detail Transaksi
            $table->string('service_type'); // delivery, takeaway, dinein
            $table->string('payment_method'); // cod, transfer, qris
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['pending', 'paid', 'completed', 'cancelled'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};