<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Banner;
use App\Models\Promo;
use App\Models\Order; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage; 

class CustomerController extends Controller
{
    public function landing()
    {
        $banners = Banner::where('is_active', true)->orderBy('order', 'asc')->get();
        
        try {
            $promos = Promo::latest()->take(3)->get();
        } catch (\Exception $e) {
            $promos = [];
        }
        
        return view('customer.landing', compact('banners', 'promos'));
    }

    // --- FUNGSI MENU BARU DENGAN FILTER ---
    public function menu(Request $request)
    {
        $menus = Menu::orderBy('is_available', 'desc');

        // 1. FILTER BERDASARKAN SEARCH (NAMA)
        if ($request->filled('search')) {
            $menus->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. FILTER BERDASARKAN KATEGORI
        if ($request->filled('category') && $request->category !== 'all') {
            $menus->where('category', $request->category);
        }

        $menus = $menus->get();

        $cart = Session::get('cart', []);
        
        $itemCount = collect($cart)->sum('quantity');
        $grandTotal = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        $cartData = ['itemCount' => $itemCount, 'grandTotal' => $grandTotal];

        // Ambil semua kategori unik yang tersedia
        $categories = ['Appetizer', 'Main Course', 'Dessert', 'Drink'];
        
        return view('customer.menu', compact('menus', 'cartData', 'categories'));
    }

    public function profile()
    {
        $user = auth()->user();

        $orders = Order::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('customer.profile', compact('user', 'orders'));
    }

    // ---UPDATE FOTO PROFIL ---
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
        ]);

        $user = auth()->user();

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Simpan foto baru ke folder 'profile-photos'
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        return back()->with('status', 'Foto profil berhasil diperbarui.');
    }
}