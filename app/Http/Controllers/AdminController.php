<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Banner;
use App\Models\Order;
use App\Models\Promo; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // 1. HALAMAN VIEW (DASHBOARD & FITUR)

    private function getMonthlySalesData()
    {
        $months = [];
        $salesData = [];
        
        $date = Carbon::now()->subMonths(5);
        
        for ($i = 0; $i < 6; $i++) {
            $monthName = $date->translatedFormat('M');
            $year = $date->format('Y');

            // Hitung total dari status 'paid', 'cooking', DAN 'completed'
            $total = Order::whereIn('status', ['paid', 'cooking', 'completed'])
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('total_price');

            $months[] = $monthName . ' ' . $year;
            $salesData[] = $total;
            
            $date->addMonth();
        }

        return ['months' => $months, 'salesData' => $salesData];
    }
    
    public function index()
    {
        $totalRevenue = Order::whereIn('status', ['paid', 'cooking', 'completed'])->sum('total_price'); 
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalMenus = Menu::count();
        $chartData = $this->getMonthlySalesData();

        return view('admin.index', compact('totalRevenue', 'pendingOrders', 'totalMenus', 'chartData'));
    }

    public function menuIndex()
    {
        $menus = Menu::orderBy('created_at', 'desc')->get();
        $categories = ['Appetizer', 'Main Course', 'Dessert', 'Drink'];
        return view('admin.menu', compact('menus', 'categories'));
    }

    public function bannerIndex()
    {
        $banners = Banner::orderBy('order', 'asc')->get();
        $promos = Promo::latest()->get(); 
        return view('admin.banner', compact('banners', 'promos'));
    }

    public function ordersIndex()
    {
        $orders = Order::with('items')->orderBy('created_at', 'desc')->get();
        return view('admin.orders', compact('orders'));
    }

    // 2. LOGIKA MENU MAKANAN (CRUD + STOK)

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required|string', 
            'stock' => 'required|integer|min:0', // Validasi Stok
            'image' => 'required|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $path = $request->file('image')->store('menu-images', 'public');
        
        $menu = Menu::create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'stock' => $request->stock, // Simpan Stok
            'image' => $path,
            // Jika stok > 0, otomatis tersedia. Jika 0, otomatis habis.
            'is_available' => $request->stock > 0 ? true : false 
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Menu berhasil ditambahkan!',
            'data' => $menu,
            'image_url' => asset('storage/' . $path)
        ]);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0', 
            'image' => 'nullable|image|max:2048' 
        ]);

        $menu->name = $request->name;
        $menu->price = $request->price;
        $menu->category = $request->category;
        $menu->stock = $request->stock; // Update Stok
        
        // Update ketersediaan berdasarkan stok baru
        $menu->is_available = $request->stock > 0 ? true : false;

        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $path = $request->file('image')->store('menu-images', 'public');
            $menu->image = $path;
        }

        $menu->save();

        return response()->json(['status' => 'success', 'message' => 'Menu berhasil diupdate!']);
    }

    public function toggle($id)
    {
        $menu = Menu::findOrFail($id);
        
        // Jika stok habis, tidak bisa diaktifkan manual 
        if ($menu->stock <= 0 && !$menu->is_available) {
             return response()->json(['status' => 'error', 'message' => 'Stok habis! Tambah stok dulu.']);
        }

        $menu->is_available = !$menu->is_available;
        $menu->save();

        return response()->json(['status' => 'success', 'new_status' => $menu->is_available]);
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->image) Storage::disk('public')->delete($menu->image);
        $menu->delete();

        return response()->json(['status' => 'success', 'message' => 'Menu dihapus']);
    }

    // 3. LOGIKA BANNER & PROMO (CRUD)

    public function storeBanner(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }
        
        $path = $request->file('image')->store('banner-images', 'public');
        
        $banner = Banner::create([
            'title' => $request->title,
            'image_path' => $path,
            'link_url' => $request->link_url,
            'is_active' => true,
            'order' => Banner::max('order') + 1 
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Banner berhasil ditambahkan!',
            'data' => $banner,
            'image_url' => asset('storage/' . $path)
        ]);
    }

    public function toggleBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return response()->json(['status' => 'success', 'new_status' => $banner->is_active]);
    }

    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->image_path) Storage::disk('public')->delete($banner->image_path);
        $banner->delete();

        return response()->json(['status' => 'success', 'message' => 'Banner dihapus']);
    }

    public function storePromo(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', 
            'link' => 'nullable|url' 
        ]);

        $path = $request->file('image')->store('promo-images', 'public');
        
        Promo::create([
            'image' => $path,
            'link' => $request->link,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Promo berhasil ditambahkan!');
    }

    public function deletePromo($id)
    {
        $promo = Promo::findOrFail($id);
        if($promo->image) {
            Storage::disk('public')->delete($promo->image);
        }
        $promo->delete();
        
        return redirect()->back()->with('success', 'Promo dihapus!');
    }

    // 4. LOGIKA PESANAN (STATUS & BATAL)

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            
            $request->validate([
                'status' => 'required|in:pending,paid,cooking,completed,cancelled'
            ]);

            $order->status = $request->status;
            $order->save();

            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui menjadi ' . strtoupper($request->status));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    public function handleCancellation(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $action = $request->action; 

        if ($action == 'approve') {
            $order->status = 'cancelled';
            // Opsional: Kembalikan stok jika pesanan dibatalkan

        } else {
            $order->cancellation_note = null;
        }

        $order->save();

        return back()->with('success', $action == 'approve' ? 'Pembatalan disetujui.' : 'Pembatalan ditolak, pesanan dilanjutkan.');
    }
}