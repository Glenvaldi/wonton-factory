<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class TrackOrderController extends Controller
{
    public function index()
    {
        return view('customer.track');
    }

    public function search(Request $request)
    {
        // Validasi input
        $request->validate([
            'order_number' => 'required|string',
            'phone'        => 'required|numeric',
        ]);

        // Cari Order 
        $order = Order::with('items')
                    ->where('order_number', trim($request->order_number))
                    ->where('phone', trim($request->phone))
                    ->first();

        if (!$order) {
            // Jika tidak ketemu, kembali dengan pesan error
            return back()->with('error', 'Pesanan tidak ditemukan. Periksa Nomor Order & No HP Anda.');
        }

        // Jika ketemu, tampilkan halaman track dengan data order
        return view('customer.track', compact('order'));
    }
}