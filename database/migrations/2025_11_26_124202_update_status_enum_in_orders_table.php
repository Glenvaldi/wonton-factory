<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Jangan lupa ini

return new class extends Migration
{
    public function up(): void
    {
        // Kita ubah kolom status agar menerima 'cooking'
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'paid', 'cooking', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Kembalikan ke asal (tanpa cooking) jika di-rollback
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'paid', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};