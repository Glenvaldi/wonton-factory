<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // Hitung Total
    private function getCartData(array $cart)
    {
        $itemCount = collect($cart)->sum('quantity');
        $grandTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return [
            'itemCount' => $itemCount,
            'grandTotal' => $grandTotal,
            'grandTotalFormatted' => number_format($grandTotal, 0, ',', '.')
        ];
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (count($cart) === 0) {
            return redirect()->route('menu.index')->with('error', 'Keranjang kosong.');
        }

        $menuIds = array_keys($cart);
        $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');
        
        $stockChanged = false; 

        foreach ($cart as $id => $details) {
            if (isset($menus[$id])) {
                $cart[$id]['image'] = $menus[$id]->image; 
                

                // Jika jumlah di keranjang melebihi stok database, turunkan jumlahnya
                $currentStock = $menus[$id]->stock;
                if ($cart[$id]['quantity'] > $currentStock) {
                    $cart[$id]['quantity'] = $currentStock; 
                    $stockChanged = true;
                }
                
                // Jika stok habis (0), hapus item dari keranjang
                if ($currentStock <= 0) {
                    unset($cart[$id]);
                    $stockChanged = true;
                }
            }
        }
        
        session()->put('cart', $cart); 

        // Jika ada perubahan otomatis karena stok kurang
        if ($stockChanged) {
            session()->flash('error', 'Beberapa jumlah item disesuaikan karena stok terbatas.');
        }

        // Cek lagi jika keranjang jadi kosong setelah validasi stok
        if (count($cart) === 0) {
            return redirect()->route('menu.index')->with('error', 'Maaf, menu yang Anda pilih baru saja habis.');
        }

        $cartData = $this->getCartData($cart);
        return view('checkout.checkout', compact('cart', 'cartData'));
    }

    public function updateCart(Request $request)
    {
        $id = $request->id;
        $action = $request->action; 
        
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $menu = Menu::find($id);

            if($action == 'increase') {
                // --- LOGIKA STOK 2: Cek stok sebelum nambah ---
                if ($menu && $cart[$id]['quantity'] >= $menu->stock) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Stok maksimal tercapai! Hanya tersisa ' . $menu->stock . ' porsi.'
                    ]);
                }
                $cart[$id]['quantity']++;
            } else {
                $cart[$id]['quantity']--;
                if($cart[$id]['quantity'] < 1) $cart[$id]['quantity'] = 1;
            }

            session()->put('cart', $cart);

            $itemSubtotal = $cart[$id]['price'] * $cart[$id]['quantity'];
            $cartData = $this->getCartData($cart);

            return response()->json([
                'success' => true,
                'newQty' => $cart[$id]['quantity'],
                'itemSubtotal' => number_format($itemSubtotal, 0, ',', '.'),
                'grandTotal' => $cartData['grandTotalFormatted']
            ]);
        }
    }

    public function removeItem(Request $request)
    {
        $cart = session()->get('cart');
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        $cartData = $this->getCartData($cart);

        return response()->json([
            'success' => true,
            'isEmpty' => count($cart) == 0, 
            'grandTotal' => $cartData['grandTotalFormatted']
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required',
            'service_type' => 'required|in:dinein,takeaway,delivery',
            'address' => 'required_if:service_type,delivery', 
            'table_number' => 'required_if:service_type,dinein', 
        ]);

        $cart = session()->get('cart', []);
        if(count($cart) == 0) return redirect()->route('menu.index');

        $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        try {
            DB::beginTransaction();

            // --- Validasi Akhir & Pengurangan Stok ---
            foreach($cart as $id => $item) {
                // rebutan beli di detik yang sama)
                $menu = Menu::lockForUpdate()->find($id);
                
                if (!$menu || $menu->stock < $item['quantity']) {
                    DB::rollBack();
                    return back()->with('error', 'Maaf, stok untuk menu "' . $item['name'] . '" tidak mencukupi saat ini.');
                }
                
                // KURANGI STOK
                $menu->decrement('stock', $item['quantity']);
                
                // Jika stok jadi 0, set unavailable 
                if($menu->stock == 0) {
                    $menu->update(['is_available' => false]);
                }
            }

            $order = new Order();
            $order->order_number = 'ORD-' . strtoupper(substr(uniqid(), -5)); 
            $order->user_id = auth()->id(); 
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->service_type = $request->service_type;
            $order->payment_method = $request->payment_method;
            $order->total_price = $totalPrice;
            $order->status = 'pending'; 

            if ($request->service_type == 'delivery') {
                $order->address = $request->address;
            } elseif ($request->service_type == 'dinein') {
                $order->table_number = $request->table_number;
            }
            
            $order->save();

            foreach($cart as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $id,
                    'menu_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            session()->forget('cart');
            return redirect()->route('checkout.payment', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage())->withInput();
        }
    }

    public function payment($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('customer.payment', compact('order'));
    }

    public function success($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('customer.success', compact('order'));
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $order->payment_proof = $path;
            $order->save();
        }

        return back()->with('success', 'Bukti pembayaran berhasil dikirim! Mohon tunggu konfirmasi Admin.');
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status == 'pending' && !$order->payment_proof) {
            
            // Jika Anda ingin stok kembali otomatis saat user batal sendiri)
            foreach($order->items as $item) {
                $menu = Menu::find($item->menu_id);
                if($menu) {
                    $menu->increment('stock', $item->quantity);
                    if(!$menu->is_available && $menu->stock > 0) {
                         $menu->update(['is_available' => true]);
                    }
                }
            }

            $order->status = 'cancelled';
            $order->save();
            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $order->cancellation_note = $request->reason;
        $order->save();

        return back()->with('success', 'Permintaan pembatalan dikirim. Menunggu persetujuan Admin.');
    }
}