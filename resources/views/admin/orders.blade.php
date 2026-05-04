@extends('layouts.admin')

@section('content')

<div class="container-fluid p-0">
    
    {{-- NOTIFIKASI --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 border-start border-5 border-success shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in-up">
        <div>
            <h2 class="fw-bold text-dark mb-1">Pesanan Masuk</h2>
            <p class="text-muted mb-0">Kelola pembayaran dan proses dapur.</p>
        </div>
        <button class="btn btn-light border shadow-sm rounded-pill px-4 hover-scale" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise me-2"></i> Refresh Data
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate-fade-in-up delay-100">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase fw-bold">
                    <tr>
                        <th class="ps-4 py-3">ID Order</th>
                        <th>Pelanggan & Kontak</th> {{-- JUDUL DIUPDATE --}}
                        <th>Total & Metode</th>
                        <th class="text-center">Status Pembayaran</th>
                        <th class="text-center">Proses Dapur (Aksi)</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($orders as $order)

                    {{-- ========================================================== --}}
                    {{-- LOGIKA PEMBUATAN PESAN WHATSAPP OTOMATIS --}}
                    {{-- ========================================================== --}}
                    @php
                        // 1. Format Nomor HP (08xx -> 628xx)
                        $phone = $order->phone;
                        if(substr(trim($phone), 0, 1) == '0') {
                            $phone = '62' . substr(trim($phone), 1);
                        }

                        // 2. Terjemahkan Status untuk Pesan
                        $statusIndo = '';
                        $emoji = '';
                        if($order->status == 'pending') {
                            $statusIndo = 'Menunggu Pembayaran'; 
                            $emoji = '⏳';
                        } elseif($order->status == 'paid') {
                            $statusIndo = 'Pembayaran Diterima'; 
                            $emoji = '✅';
                        } elseif($order->status == 'cooking') {
                            $statusIndo = 'Sedang Dimasak/Disiapkan'; 
                            $emoji = '🍳';
                        } elseif($order->status == 'completed') {
                            $statusIndo = 'Selesai / Siap Diantar'; 
                            $emoji = '🚀';
                        } elseif($order->status == 'cancelled') {
                            $statusIndo = 'Dibatalkan'; 
                            $emoji = '❌';
                        }

                        // 3. Susun Isi Pesan
                        $msg  = "Halo Kak *{$order->name}*! 👋\n\n";
                        $msg .= "Update status pesanan WontonFactory *#{$order->order_number}*:\n";
                        $msg .= "Status: *{$statusIndo}* {$emoji}\n";
                        
                        if($order->cancellation_note && $order->status == 'cancelled'){
                            $msg .= "Alasan: _{$order->cancellation_note}_\n";
                        }
                        
                        $msg .= "Total: Rp " . number_format($order->total_price,0,',','.') . "\n\n";
                        $msg .= "Terima kasih telah memesan di WontonFactory! 🥟";

                        // 4. Encode URL
                        $waLink = "https://wa.me/{$phone}?text=" . urlencode($msg);
                    @endphp
                    {{-- ========================================================== --}}

                    <tr class="{{ $order->cancellation_note ? 'bg-danger bg-opacity-10 border-start border-5 border-danger' : '' }} transition-all">
                        
                        {{-- 1. ID ORDER --}}
                        <td class="ps-4 py-3">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">#{{ $order->order_number }}</span>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i> {{ $order->created_at->format('d M, H:i') }}
                                </small>
                            </div>
                            @if($order->cancellation_note)
                                <span class="badge bg-danger mt-2">MINTA BATAL</span>
                            @endif
                        </td>

                        {{-- 2. PELANGGAN (DITAMBAH TOMBOL WA) --}}
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ $order->name }}</span>
                                <small class="text-muted mb-2">{{ $order->phone }}</small>
                            </div>
                            
                            {{-- Tombol Kirim Pesan --}}
                            <div class="d-flex gap-2">
                                <a href="{{ $waLink }}" target="_blank" class="btn btn-sm btn-success text-white rounded-pill px-3" style="font-size: 0.75rem;" title="Kirim Status ke WA">
                                    <i class="bi bi-whatsapp me-1"></i> Hubungi
                                </a>
                                
                                <span class="badge bg-light text-dark border fw-normal d-flex align-items-center">
                                    @if($order->service_type == 'dinein') <i class="bi bi-shop me-1"></i> Dine In ({{ $order->table_number }})
                                    @elseif($order->service_type == 'delivery') <i class="bi bi-truck me-1"></i> Delivery
                                    @else <i class="bi bi-bag me-1"></i> Takeaway
                                    @endif
                                </span>
                            </div>
                        </td>

                        {{-- 3. TOTAL & METODE --}}
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-black text-dark fs-6">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border">{{ strtoupper($order->payment_method) }}</span>
                                    
                                    @if($order->payment_method == 'qris')
                                        @if($order->payment_proof)
                                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-primary text-decoration-none small fw-bold">
                                                <i class="bi bi-image"></i> Cek Bukti
                                            </a>
                                        @else
                                            <span class="text-danger small fst-italic">No Bukti</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- 4. STATUS PEMBAYARAN (ACC) --}}
                        <td class="text-center">
                            @if($order->status == 'cancelled')
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i> Dibatalkan</span>
                            
                            @elseif($order->status == 'pending')
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                    
                                    <form action="{{ route('admin.order.update_status', $order->id) }}" method="POST">
                                        @csrf <input type="hidden" name="status" value="paid">
                                        <button class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" type="submit" onclick="return confirm('Konfirmasi pembayaran ini?')">
                                            <i class="bi bi-check-lg"></i> ACC Bayar
                                        </button>
                                    </form>
                                </div>

                            @else
                                <span class="badge bg-success px-3 py-2"><i class="bi bi-cash-coin me-1"></i> LUNAS</span>
                            @endif
                        </td>

                        {{-- 5. PROSES DAPUR (AKSI MASAK & BATALKAN) --}}
                        <td class="text-center pe-4">
                            
                            {{-- KASUS 1: USER MINTA BATAL --}}
                            @if($order->cancellation_note)
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <small class="text-danger fw-bold">Alasan: "{{ Str::limit($order->cancellation_note, 15) }}"</small>
                                    <div class="btn-group shadow-sm" role="group">
                                        <form action="{{ route('admin.order.handle_cancel', $order->id) }}" method="POST">
                                            @csrf <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Tolak Batal"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                        <form action="{{ route('admin.order.handle_cancel', $order->id) }}" method="POST">
                                            @csrf <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Setujui Batal">ACC Batal</button>
                                        </form>
                                    </div>
                                </div>

                            {{-- KASUS 2: SUDAH BATAL / SELESAI --}}
                            @elseif($order->status == 'cancelled')
                                <span class="text-muted small">- Order Batal -</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-secondary"><i class="bi bi-flag-fill me-1"></i> Order Selesai</span>

                            {{-- KASUS 3: PROSES AKTIF --}}
                            @else
                                <div class="d-flex flex-column align-items-center gap-2">
                                    
                                    {{-- Tombol Utama Sesuai Status --}}
                                    @if($order->status == 'pending')
                                        <span class="text-muted small fst-italic">Menunggu Pembayaran...</span>

                                    @elseif($order->status == 'paid')
                                        <form action="{{ route('admin.order.update_status', $order->id) }}" method="POST">
                                            @csrf <input type="hidden" name="status" value="cooking">
                                            <button class="btn btn-warning text-dark btn-sm rounded-pill px-3 fw-bold shadow-sm" type="submit">
                                                <i class="bi bi-fire me-1"></i> Mulai Masak
                                            </button>
                                        </form>

                                    @elseif($order->status == 'cooking')
                                        <div class="badge bg-info text-dark mb-1"><i class="bi bi-fire"></i> Sedang Dimasak</div>
                                        <form action="{{ route('admin.order.update_status', $order->id) }}" method="POST">
                                            @csrf <input type="hidden" name="status" value="completed">
                                            <button class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm" type="submit">
                                                <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                            </button>
                                        </form>
                                    @endif

                                    {{-- TOMBOL BATALKAN PAKSA --}}
                                    <form action="{{ route('admin.order.update_status', $order->id) }}" method="POST" class="mt-1">
                                        @csrf <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-link text-danger text-decoration-none p-0" style="font-size: 0.75rem;" onclick="return confirm('Yakin batalkan paksa pesanan ini? (Misal: Stok Habis)')">
                                            <i class="bi bi-x-circle"></i> Batalkan Pesanan
                                        </button>
                                    </form>

                                </div>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted fs-5">Belum ada pesanan masuk hari ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection