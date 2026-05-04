@extends('layouts.admin')

@section('content')

<div class="container-fluid p-0">
    
    {{-- NOTIFIKASI --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 border-start border-5 border-success shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1">Kelola Menu</h2>
            <p class="text-muted mb-0">Atur varian makanan, minuman, dan stok.</p>
        </div>
        <button class="btn btn-danger rounded-pill px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddMenu">
            <i class="bi bi-plus-lg me-2"></i> Tambah Menu
        </button>
    </div>

    {{-- GRID MENU (Card Style) --}}
    <div class="row g-4">
        @forelse($menus as $menu)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-shadow transition-all">
                
                {{-- Gambar Menu --}}
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top object-fit-cover" height="220" alt="{{ $menu->name }}">
                    
                    {{-- Badge Kategori --}}
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-white text-dark shadow-sm rounded-pill px-3 py-2 text-uppercase fw-bold" style="font-size: 0.7rem;">
                            {{ $menu->category ?? 'Menu' }}
                        </span>
                    </div>

                    {{-- Overlay jika habis atau tidak available --}}
                    @if(!$menu->is_available)
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <span class="badge border border-white text-white px-3 py-2 mb-2">TIDAK TERSEDIA</span>
                            @if($menu->stock <= 0)
                                <br><small class="text-white">Stok Habis</small>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-2">
                        <h5 class="card-title fw-bold text-dark mb-1">{{ $menu->name }}</h5>
                    </div>
                    
                    <h5 class="text-danger fw-bold mb-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</h5>
                    
                    {{-- TAMPILAN STOK --}}
                    <div class="mb-3">
                        <span class="badge {{ $menu->stock > 5 ? 'bg-light text-dark border' : 'bg-danger text-white' }} rounded-pill px-3">
                            Stok: {{ $menu->stock }}
                        </span>
                    </div>

                    <div class="mt-auto pt-3 border-top border-light d-flex justify-content-between align-items-center">
                        
                        {{-- Toggle Status --}}
                        <div class="form-check form-switch" title="Aktifkan/Nonaktifkan Menu">
                            <input class="form-check-input toggle-menu cursor-pointer" type="checkbox" data-id="{{ $menu->id }}" {{ $menu->is_available ? 'checked' : '' }} style="transform: scale(1.2);">
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm rounded-circle edit-menu" 
                                data-id="{{ $menu->id }}" 
                                data-name="{{ $menu->name }}" 
                                data-price="{{ $menu->price }}"
                                data-category="{{ $menu->category ?? '' }}"
                                data-stock="{{ $menu->stock }}" 
                                title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm rounded-circle delete-menu" 
                                data-id="{{ $menu->id }}"
                                title="Hapus">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 bg-white rounded-4 border border-dashed">
                <i class="bi bi-egg-fried fs-1 text-muted mb-3 d-block"></i>
                <h5 class="text-muted fw-bold">Belum ada menu.</h5>
                <button class="btn btn-danger btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalAddMenu">Tambah Sekarang</button>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH MENU --}}
<div class="modal fade" id="modalAddMenu" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white border-0 p-4">
                <h5 class="modal-title fw-bold">Tambah Menu Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formMenu" class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Nama Menu</label>
                        <input type="text" name="name" class="form-control bg-light border-0 p-3" required placeholder="Contoh: Nasi Goreng">
                    </div>
                    
                    {{-- Baris Harga & Stok --}}
                    <div class="col-6">
                        <label class="form-label small fw-bold text-uppercase text-muted">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control bg-light border-0 p-3" required placeholder="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-uppercase text-muted">Stok Awal</label>
                        <input type="number" name="stock" class="form-control bg-light border-0 p-3" required placeholder="0">
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Kategori</label>
                        <select name="category" class="form-select bg-light border-0 p-3" required>
                            <option value="">Pilih...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Foto</label>
                        <input type="file" name="image" class="form-control bg-light border-0 p-3" required>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-danger w-100 py-3 rounded-3 fw-bold">SIMPAN MENU</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT MENU --}}
<div class="modal fade" id="modalEditMenu" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold">Edit Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formEditMenu" class="row g-3">
                    @csrf
                    <input type="hidden" name="id" id="edit_id"> 
                    
                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Nama Menu</label>
                        <input type="text" name="name" id="edit_name" class="form-control bg-light border-0 p-3" required>
                    </div>

                    {{-- Baris Harga & Stok --}}
                    <div class="col-6">
                        <label class="form-label small fw-bold text-uppercase text-muted">Harga</label>
                        <input type="number" name="price" id="edit_price" class="form-control bg-light border-0 p-3" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-uppercase text-muted">Stok</label>
                        <input type="number" name="stock" id="edit_stock" class="form-control bg-light border-0 p-3" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Kategori</label>
                        <select name="category" id="edit_category" class="form-select bg-light border-0 p-3" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Ganti Foto (Opsional)</label>
                        <input type="file" name="image" class="form-control bg-light border-0 p-3">
                        <small class="text-muted">*Kosongkan jika tidak ingin mengganti foto</small>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-dark w-100 py-3 rounded-3 fw-bold">SIMPAN PERUBAHAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .transition-all { transition: all 0.3s ease; }
    .form-check-input:checked { background-color: #cc002d; border-color: #cc002d; }
</style>

@push('scripts')
<script>
    // --- JAVASCRIPT CRUD MENU ---
    
    // 1. Tambah Menu
    $('#formMenu').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('admin.menu.store') }}", 
            type: "POST", 
            data: formData, 
            contentType: false, 
            processData: false,
            success: function(res) {
                $('#modalAddMenu').modal('hide'); 
                $('#formMenu')[0].reset();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Menu ditambahkan', confirmButtonColor: '#cc002d' });
                setTimeout(() => location.reload(), 1000); 
            },
            error: function(xhr) { 
                Swal.fire('Error', 'Gagal menyimpan menu. Pastikan semua data terisi.', 'error'); 
            }
        });
    });
    
    // 2. Buka Modal Edit & Isi Data (Termasuk Stok)
    $(document).on('click', '.edit-menu', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let price = $(this).data('price');
        let category = $(this).data('category');
        let stock = $(this).data('stock'); // Ambil data stok

        $('#edit_id').val(id); 
        $('#edit_name').val(name); 
        $('#edit_price').val(price); 
        $('#edit_category').val(category);
        $('#edit_stock').val(stock); // Isi form stok

        $('#modalEditMenu').modal('show');
    });
    
    // 3. Simpan Perubahan Edit
    $('#formEditMenu').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        let formData = new FormData(this);
        
        // Laravel method spoofing untuk PUT/PATCH via FormData
        // (Opsional, tergantung route Anda, tapi Controller Anda pakai POST untuk update dengan file, jadi aman)
        
        $.ajax({
            url: `/admin/menu/${id}/update`, 
            type: "POST", 
            data: formData, 
            contentType: false, 
            processData: false,
            success: function(res) {
                $('#modalEditMenu').modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Menu diupdate', confirmButtonColor: '#cc002d' });
                setTimeout(() => location.reload(), 1000); 
            },
            error: function() { Swal.fire('Error', 'Gagal update menu', 'error'); }
        });
    });

    // 4. Hapus Menu
    $('.delete-menu').on('click', function() {
        let id = $(this).data('id');
        Swal.fire({ 
            title: 'Hapus Menu?', text: "Tidak bisa dikembalikan!", icon: 'warning', 
            showCancelButton: true, confirmButtonColor: '#cc002d', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, Hapus!' 
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({ url: `/admin/menu/${id}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: function() { location.reload(); } });
            }
        });
    });

    // 5. Toggle Status (Tapi dicegah Controller jika stok 0)
    $('.toggle-menu').on('change', function() {
        let id = $(this).data('id');
        let checkbox = $(this); // Simpan elemen checkbox
        
        $.post(`/admin/menu/${id}/toggle`, { _token: '{{ csrf_token() }}' })
            .fail(function(xhr) {
                // Jika error (misal stok habis), kembalikan posisi checkbox
                checkbox.prop('checked', !checkbox.prop('checked'));
                let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal mengubah status';
                Swal.fire('Gagal', msg, 'error');
            });
    });
</script>
@endpush
@endsection