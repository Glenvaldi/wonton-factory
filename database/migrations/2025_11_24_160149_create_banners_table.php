<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus tabel jika sudah ada (untuk menghindari error "table exists")
        Schema::dropIfExists('banners');

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            
            // Field Sederhana (Single Image Mode)
            $table->string('title');       // Judul (hanya untuk admin)
            $table->string('image_path');  // Foto Banner (Gambar Panjang)
            $table->string('link_url')->nullable(); // Link tujuan
            $table->integer('order')->default(0);   // Urutan
            $table->boolean('is_active')->default(true); // Status
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};