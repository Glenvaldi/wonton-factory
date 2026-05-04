<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // Menghubungkan ke tabel orders (Wajib ada)
            // onDelete('cascade') berarti kalau order dihapus, itemnya ikut terhapus
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // Data Menu (Simpan juga namanya, jaga-jaga kalau menu asli dihapus/diganti nama)
            $table->unsignedBigInteger('menu_id');
            $table->string('menu_name');
            
            // Detail Harga & Jumlah
            $table->integer('quantity');
            $table->decimal('price', 15, 2);    // Harga satuan saat beli
            $table->decimal('subtotal', 15, 2); // quantity * price
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};