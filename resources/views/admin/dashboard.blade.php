@extends('layouts.admin')

@section('content')

<div class="container-fluid">

    {{-- NOTIFIKASI SUKSES / ERROR --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dashboard Overview</h2>
            <p class="text-muted mb-0">Halo Admin, inilah ringkasan bisnis hari ini.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-white text-dark border px-3 py-2 shadow-sm rounded-pill">
                <i class="bi bi-calendar-event me-2"></i>{{ now()->format('d M Y') }}
            </span>
        </div>
    </div>

    {{-- 1. KARTU STATISTIK --}}
    <div class="row g-4 mb-5" id="dashboard">
        <div class="col-md-4">
            <div class="card-modern p-4 d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success">
                    <i class="bi bi-cash-stack fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Total Pendapatan</p>
                    <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-modern p-4 d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3 text-warning">
                    <i class="bi bi-bag-check fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Pesanan Pending</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-modern p-4 d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary">
                    <i class="bi bi-grid fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Total Menu</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalMenus }} Item</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. TABEL MENU --}}
    <div class="card-modern mb-5" id="menu">
        <div class="card-header-modern">
            <h5 class="fw-bold mb-0">📋 Daftar Menu</h5>
            <button class="btn btn-primary btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAddMenu">
                <i class="bi bi-plus-lg me-1"></i> Tambah Menu
            </button>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">Produk</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr id="menu-row-{{ $menu->id }}">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $menu->image) }}" class="rounded-3 me-3 shadow-sm" width="50" height="50" style="object-fit:cover">
                                <span class="fw-bold text-dark">{{ $menu->name }}</span>
                                <span class="badge bg-secondary ms-2 small">{{ $menu->category }}</span> 
                            </div>
                        </td>
                        <td class="fw-bold text-success">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-menu" type="checkbox" data-id="{{ $menu->id }}" {{ $menu->is_available ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted status-label">{{ $menu->is_available ? 'Tersedia' : 'Habis' }}</label>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-light btn-sm text-warning me-1 edit-menu" 
                                data-id="{{ $menu->id }}" 
                                data-name="{{ $menu->name }}" 
                                data-price="{{ $menu->price }}"
                                data-category="{{ $menu->category ?? '' }}"> {{-- [UPDATE] Tambahkan data-category --}}
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-light btn-sm text-danger delete-menu" data-id="{{ $menu->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada menu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 3. TABEL PESANAN MASUK (FITUR UPDATE STATUS & PEMBATALAN) --}}
    <div class="card-modern" id="orders">
        <div class="card-header-modern">
            <h5 class="fw-bold mb-0">🛎️ Pesanan Masuk</h5>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Bukti Bayar</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                
                {{-- BAGIAN BODY TABEL YANG DIPERBARUI --}}
                <tbody>
                    @forelse($orders as $order)
                    <tr class="{{ $order->cancellation_note ? 'table-danger' : '' }}"> {{-- Baris merah jika minta batal --}}
                        <td class="ps-4">
                            <span class="badge bg-dark">{{ $order->order_number }}</span>
                            <div class="small fw-bold mt-1">{{ $order->name }}</div>
                            <small class="text-muted">{{ ucfirst($order->service_type) }}</small>
                            
                            @if($order->service_type == 'dinein')
                                <span class="badge bg-warning text-dark ms-1">{{ $order->table_number }}</span>
                            @elseif($order->service_type == 'delivery')
                                <div class="text-muted small fst-italic" style="font-size:0.7em; max-width:150px">{{ Str::limit($order->address, 30) }}</div>
                            @endif

                            {{-- ALERT JIKA ADA PERMINTAAN PEMBATALAN --}}
                            @if($order->cancellation_note)
                                <div class="mt-2 bg-white border border-danger text-danger p-2 rounded small shadow-sm">
                                    <strong><i class="bi bi-exclamation-circle-fill"></i> Minta Batal:</strong><br>
                                    "{{ $order->cancellation_note }}"
                                </div>
                            @endif
                        </td>

                        {{-- KOLOM BUKTI BAYAR --}}
                        <td>
                            <div class="small fw-bold text-muted mb-1">{{ strtoupper($order->payment_method) }}</div>
                            @if($order->payment_method == 'qris')
                                @if($order->payment_proof)
                                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-info py-0" style="font-size: 0.75rem;">
                                        <i class="bi bi-eye"></i> Cek Foto
                                    </a>
                                @else
                                    <span class="badge bg-light text-danger border border-danger">Belum Upload</span>
                                @endif
                            @else
                                <span class="badge bg-light text-secondary border">Tunai / COD</span>
                            @endif
                        </td>

                        <td class="fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>

                        <td>
                            @if($order->status == 'cancelled') <span class="badge bg-danger">Dibatalkan</span>
                            @elseif($order->status == 'pending') <span class="badge bg-secondary">Pending</span>
                            @elseif($order->status == 'paid') <span class="badge bg-primary">Dibayar</span>
                            @elseif($order->status == 'cooking') <span class="badge bg-warning text-dark">Dimasak</span>
                            @elseif($order->status == 'completed') <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>

                        {{-- TOMBOL AKSI --}}
                        <td class="text-end pe-4">
                            
                            {{-- SKENARIO 1: JIKA ADA PERMINTAAN BATAL --}}
                            @if($order->cancellation_note)
                                <div class="d-flex gap-1 justify-content-end">
                                    {{-- Tolak Batal --}}
                                    <form action="{{ route('admin.order.handle_cancel', $order->id) }}" method="POST">
                                        @csrf <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-sm btn-secondary" title="Tolak (Lanjut Pesanan)">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                    {{-- Setuju Batal --}}
                                    <form action="{{ route('admin.order.handle_cancel', $order->id) }}" method="POST">
                                        @csrf <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Setujui Pembatalan">
                                            ACC Batal
                                        </button>
                                    </form>
                                </div>

                            {{-- SKENARIO 2: JIKA PESANAN NORMAL --}}
                            @elseif($order->status != 'cancelled')
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Proses
                                    </button>
                                    <ul class="dropdown-menu shadow border-0">
                                        <li>
                                            <form action="{{ route('admin.order.update_status', $order->id) }}" method="POST">
                                                @csrf <input type="hidden" name="status" value="paid">
                                                <button class="dropdown-item text-primary" type="submit"><i class="bi bi-check-circle me-2"></i> Terima Bayar (ACC)</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.order.update_status', $order->id) }}" method="POST">
                                                @csrf <input type="hidden" name="status" value="cooking">
                                                <button class="dropdown-item text-warning" type="submit"><i class="bi bi-fire me-2"></i> Mulai Masak</button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.order.update_status', $order->id) }}" method="POST">
                                                @csrf <input type="hidden" name="status" value="completed">
                                                <button class="dropdown-item text-success fw-bold" type="submit"><i class="bi bi-check-all me-2"></i> Selesai / Diantar</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endif

                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL AREA --}}

{{-- 1. MODAL TAMBAH MENU --}}
<div class="modal fade" id="modalAddMenu" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="formMenu">
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control bg-light border-0" placeholder="Nama Menu" required>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="price" class="form-control bg-light border-0" placeholder="Harga (Rp)" required>
                    </div>

                    {{-- [UPDATE] INPUT KATEGORI --}}
                    <div class="mb-3">
                        <label class="small text-muted mb-1">Kategori</label>
                        <select name="category" class="form-control bg-light border-0" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Appetizer">Appetizer</option>
                            <option value="Main Course">Main Course</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Drink">Drink</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <input type="file" name="image" class="form-control bg-light border-0" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 2. MODAL EDIT MENU --}}
<div class="modal fade" id="modalEditMenu" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="formEditMenu">
                    @csrf
                    <input type="hidden" name="id" id="edit_id"> 
                    
                    <div class="mb-3">
                        <label class="small text-muted">Nama Menu</label>
                        <input type="text" name="name" id="edit_name" class="form-control bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Harga (Rp)</label>
                        <input type="number" name="price" id="edit_price" class="form-control bg-light border-0" required>
                    </div>
                    
                    {{-- [UPDATE] INPUT KATEGORI --}}
                    <div class="mb-3">
                        <label class="small text-muted">Kategori</label>
                        <select name="category" id="edit_category" class="form-control bg-light border-0" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Appetizer">Appetizer</option>
                            <option value="Main Course">Main Course</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Drink">Drink</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="small text-muted">Ganti Gambar (Opsional)</label>
                        <input type="file" name="image" class="form-control bg-light border-0">
                        <small class="text-muted fst-italic">*Biarkan kosong jika tidak ingin ganti gambar.</small>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 3. MODAL TAMBAH BANNER --}}
<div class="modal fade" id="modalAddBanner" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Upload Banner Utama</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="formBanner">
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control bg-light border-0" placeholder="Judul Banner" required>
                    </div>
                    <div class="mb-3">
                        <input type="url" name="link_url" class="form-control bg-light border-0" placeholder="Link (Opsional)">
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted mb-1">Gambar (Format: JPG/PNG)</label>
                        <input type="file" name="image" class="form-control bg-light border-0" required>
                    </div>
                    <button type="submit" class="btn btn-info text-white w-100 rounded-pill fw-bold">Upload Banner</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // --- 1. AJAX ADD MENU ---
    $('#formMenu').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('admin.menu.store') }}", type: "POST", data: formData, contentType: false, processData: false,
            success: function(res) {
                $('#modalAddMenu').modal('hide'); $('#formMenu')[0].reset();
                Swal.fire({ icon: 'success', title: 'Berhasil', timer: 1500, showConfirmButton: false });
                setTimeout(() => location.reload(), 1000); 
            },
            error: function() { Swal.fire('Error', 'Gagal menyimpan menu', 'error'); }
        });
    });

    // --- 2. AJAX TOGGLE MENU ---
    $('.toggle-menu').on('change', function() {
        let id = $(this).data('id');
        $.post(`/admin/menu/${id}/toggle`, { _token: '{{ csrf_token() }}' });
    });

    // --- 3. AJAX EDIT MENU ---
    $(document).on('click', '.edit-menu', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let price = $(this).data('price');
        let category = $(this).data('category'); // [UPDATE] Ambil kategori
        
        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#edit_price').val(price);
        $('#edit_category').val(category); // [UPDATE] Set nilai kategori
        $('#modalEditMenu').modal('show');
    });

    // --- 4. AJAX UPDATE MENU ---
    $('#formEditMenu').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        let formData = new FormData(this);

        $.ajax({
            url: `/admin/menu/${id}/update`, type: "POST", data: formData, contentType: false, processData: false,
            success: function(res) {
                $('#modalEditMenu').modal('hide');
                Swal.fire({ icon: 'success', title: 'Update Berhasil', timer: 1500, showConfirmButton: false });
                setTimeout(() => location.reload(), 1000); 
            },
            error: function() { Swal.fire('Error', 'Gagal update menu', 'error'); }
        });
    });

    // --- 5. AJAX DELETE MENU ---
    $('.delete-menu').on('click', function() {
        let id = $(this).data('id');
        Swal.fire({ title: 'Hapus?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({ url: `/admin/menu/${id}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: function() { location.reload(); } });
            }
        });
    });

    // --- 6. AJAX ADD BANNER ---
    $('#formBanner').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('admin.banner.store') }}", type: "POST", data: formData, contentType: false, processData: false,
            success: function(res) {
                $('#modalAddBanner').modal('hide'); $('#formBanner')[0].reset();
                Swal.fire({ icon: 'success', title: 'Berhasil', timer: 1500, showConfirmButton: false });
                setTimeout(() => location.reload(), 1000);
            },
            error: function() { Swal.fire('Error', 'Gagal upload banner', 'error'); }
        });
    });

    // --- 7. AJAX TOGGLE & DELETE BANNER ---
    $('.toggle-banner').on('change', function() {
        let id = $(this).data('id');
        $.post(`/admin/banner/${id}/toggle`, { _token: '{{ csrf_token() }}' });
    });

    $('.delete-banner').on('click', function() {
        let id = $(this).data('id');
        Swal.fire({ title: 'Hapus Banner?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({ url: `/admin/banner/${id}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: function() { location.reload(); } });
            }
        });
    });
</script>
@endpush
@endsection