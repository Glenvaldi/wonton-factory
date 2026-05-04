<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;   
use Illuminate\Support\Facades\Schema; 

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {

        Schema::defaultStringLength(191);

        // GLOBAL CART DATA (LOGIKA KERANJANG)

        View::composer('*', function ($view) {
            
            // Ambil data keranjang dari session
            $cart = session()->get('cart', []);
            
            // Hitung jumlah item
            $itemCount = collect($cart)->sum('quantity');
            
            // Hitung total harga
            $grandTotal = collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            // Kirim variabel $cartData ke SEMUA view
            $view->with('cartData', [
                'itemCount' => $itemCount,
                'grandTotal' => $grandTotal,
                'grandTotalFormatted' => number_format($grandTotal, 0, ',', '.')
            ]);
        });
    }
}