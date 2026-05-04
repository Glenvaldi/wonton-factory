@extends('layouts.admin')

@section('content')

<div class="container-fluid p-0">
    
    {{-- WELCOME BANNER --}}
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-1">Dashboard Overview</h2>
        <p class="text-muted">Selamat datang kembali, Admin! Berikut laporan terkini outlet Anda.</p>
    </div>
    
    {{-- STATISTIK CARDS --}}
    <div class="row g-4 mb-5">
        
        {{-- 1. PENDAPATAN (Tetap Statis / Info Saja) --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted fw-bold text-uppercase small mb-1">Total Pendapatan</p>
                            <h3 class="fw-black text-dark mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 h-1 bg-success"></div>
                </div>
            </div>
        </div>

        {{-- 2. PESANAN PENDING (KLIK -> KE HALAMAN PESANAN) --}}
        <div class="col-md-4">
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 card-hover">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted fw-bold text-uppercase small mb-1">Perlu Diproses</p>
                                <h3 class="fw-black text-dark mb-0">{{ $pendingOrders }} <span class="fs-6 text-muted fw-normal">Pesanan</span></h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning">
                                <i class="bi bi-bell-fill fs-4"></i>
                            </div>
                        </div>
                        
                        {{-- Badge Notifikasi --}}
                        @if($pendingOrders > 0)
                            <div class="mt-3">
                                <span class="badge bg-warning text-dark animate-pulse">
                                    <i class="bi bi-exclamation-circle me-1"></i> Klik untuk proses
                                </span>
                            </div>
                        @endif
                        
                        <div class="position-absolute bottom-0 start-0 w-100 h-1 bg-warning"></div>
                    </div>
                </div>
            </a>
        </div>

        {{-- 3. TOTAL MENU (KLIK -> KE HALAMAN MENU) --}}
        <div class="col-md-4">
            <a href="{{ route('admin.menu.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 card-hover">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted fw-bold text-uppercase small mb-1">Total Menu Aktif</p>
                                <h3 class="fw-black text-dark mb-0">{{ $totalMenus }} <span class="fs-6 text-muted fw-normal">Item</span></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                                <i class="bi bi-grid-fill fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-primary small fw-bold">
                                <i class="bi bi-arrow-right-circle me-1"></i> Kelola Menu
                            </span>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100 h-1 bg-primary"></div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <div class="row g-4">
        {{-- GRAFIK PENJUALAN --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">📈 Statistik Penjualan</h5>
                    <small class="text-muted">Performa penjualan 6 bulan terakhir</small>
                </div>
                <div class="card-body px-4 pb-4">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- PESANAN TERBARU (QUICK VIEW) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Pesanan Masuk</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light text-primary fw-bold rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    {{-- Ambil 5 pesanan terakhir --}}
                    @php $recentOrders = \App\Models\Order::orderBy('created_at', 'desc')->take(5)->get(); @endphp
                    
                    <div class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                            <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action px-4 py-3 border-light">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark">#{{ $order->order_number }}</span>
                                    <span class="badge rounded-pill 
                                        {{ $order->status == 'pending' ? 'bg-warning text-dark' : '' }}
                                        {{ $order->status == 'paid' ? 'bg-primary' : '' }}
                                        {{ $order->status == 'cooking' ? 'bg-info text-dark' : '' }}
                                        {{ $order->status == 'completed' ? 'bg-success' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-danger' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $order->name }}</small>
                                    <small class="fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</small>
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i> {{ $order->created_at->diffForHumans() }}
                                </small>
                            </a>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada pesanan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- STYLE KHUSUS HALAMAN INI --}}
<style>
    .fw-black { font-weight: 800; }
    /* Efek Hover Kartu agar terasa bisa diklik */
    .card-hover { 
        transition: all 0.3s ease; 
        cursor: pointer;
    }
    .card-hover:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const chartData = @json($chartData); 
    
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(227, 24, 55, 0.8)'); // Brand Red
    gradient.addColorStop(1, 'rgba(227, 24, 55, 0.1)');

    new Chart(ctx, {
        type: 'line', 
        data: {
            labels: chartData.months,
            datasets: [{
                label: 'Total Pendapatan',
                data: chartData.salesData,
                backgroundColor: gradient,
                borderColor: '#E31837',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#E31837',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1A1A1A',
                    padding: 10,
                    titleFont: { family: 'Plus Jakarta Sans', size: 13 },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 13 },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: '#f0f0f0' },
                    ticks: { 
                        font: { family: 'Plus Jakarta Sans' },
                        callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } 
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans' } }
                }
            }
        }
    });
</script>
@endpush
@endsection