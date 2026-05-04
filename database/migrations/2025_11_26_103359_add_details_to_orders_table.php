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
        Schema::table('orders', function (Blueprint $table) {
            
            // 1. Kolom Nomor Order (Penyebab Error Utama)
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->after('id')->unique()->nullable();
            }

            // 2. Kolom User ID (Agar tidak error jika login)
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id')->nullable();
            }

            // 3. Kolom Service Type (Dine In / Takeaway / Delivery)
            if (!Schema::hasColumn('orders', 'service_type')) {
                $table->string('service_type')->after('phone')->nullable();
            }

            // 4. Kolom Nomor Meja (Khusus Dine In)
            if (!Schema::hasColumn('orders', 'table_number')) {
                $table->string('table_number')->nullable();
            }

            // 5. Kolom Alamat (Khusus Delivery)
            if (!Schema::hasColumn('orders', 'address')) {
                $table->text('address')->nullable();
            }
            
            // 6. Kolom Metode Pembayaran (Cash / QRIS)
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number', 
                'user_id', 
                'service_type', 
                'table_number', 
                'address', 
                'payment_method'
            ]);
        });
    }
};