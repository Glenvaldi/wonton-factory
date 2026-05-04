<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; 

class CartController extends Controller
{
    /**
     * Helper function untuk menghitung total dan format harga.
     */
    private function getCartData(array $cart)
    {
        $itemCount = collect($cart)->sum('quantity');
        $grandTotal = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        return [
            'itemCount' => $itemCount,
            'grandTotal' => $grandTotal,
            'grandTotalFormatted' => number_format($grandTotal, 0, ',', '.')
        ];
    }
    
    /**
     * Tampilkan halaman keranjang (jika akses langsung /keranjang).
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('customer.cart', compact('cart'));
    }

    /**
     * Tambah item ke keranjang .
     */
    public function add($id)
    {
        $menu = Menu::findOrFail($id); 
        $cart = session()->get('cart', []);

        if (!$menu->is_available) {
             return response()->json(['message' => 'Menu tidak tersedia.', 'error' => true], 400);
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'     => $menu->name,
                'price'    => $menu->price,
                'image'    => $menu->image,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return response()->json(array_merge(
            ['message' => 'Menu berhasil ditambahkan!'], 
            $this->getCartData($cart)
        ));
    }

    /**
     * Menangani logika: Update jumlah ATAU Hapus jika 0.
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $newQuantity = (int) $request->quantity;

        // Jika jumlah 0 atau kurang, HAPUS item
        if ($newQuantity <= 0) {
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
            
            // Kirim respon 'remove' a
            return response()->json(array_merge(
                ['action' => 'remove'], 
                $this->getCartData($cart)
            ));
        }

        // Jika jumlah valid, UPDATE item
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $newQuantity;
            session()->put('cart', $cart);
            
            // Hitung subtotal item ini untuk update tampilan harga per baris
            $itemTotal = $cart[$id]['price'] * $newQuantity;

            // Kirim respon 'update' beserta data harga baru
            return response()->json(array_merge(
                [
                    'action' => 'update',
                    'itemTotalFormatted' => number_format($itemTotal, 0, ',', '.')
                ], 
                $this->getCartData($cart)
            ));
        }

        return response()->json(['error' => 'Item tidak ditemukan'], 404);
    }

    /**
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json(array_merge(
            ['message' => 'Menu berhasil dihapus dari keranjang!'], 
            $this->getCartData($cart)
        ));
    }
}