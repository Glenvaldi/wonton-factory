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

    <div class="d-flex justify-content-between align-items-center mb-5 animate-fade-in-up">
        <div>
            <h2 class="fw-bold text-dark mb-1">Banner & Promo</h2>
            <p class="text-muted mb-0">Kelola tampilan depan website Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-dark rounded-pill px-4 shadow-sm hover-scale" data-bs-toggle="modal" data-bs-target="#modalAddBanner">
                <i class="bi bi-image me-2"></i> Upload Banner
            </button>
            <button class="btn btn-danger rounded-pill px-4 shadow-sm hover-scale" data-bs-toggle="modal" data-bs-target="#modalAddPromo">
                <i class="bi bi-tag-fill me-2"></i> Tambah Promo
            </button>
        </div>
    </div>

    {{-- SECTION 1: BANNER SLIDER (Card Grid) --}}
    <div class="mb-5 animate-fade-in-up delay-100">
        <h5 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-danger">&nbsp; Banner Slider Utama</h5>
        
        <div class="row g-4">
            @forelse($banners as $banner)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-shadow transition-all">
                    {{-- Gambar Banner --}}
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="card-img-top object-fit-cover" height="200" alt="Banner">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }} shadow-sm rounded-pill px-3">
                                {{ $banner->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-1">{{ $banner->title }}</h6>
                        @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" class="text-danger text-decoration-none small">
                                <i class="bi bi-link-45deg"></i> {{ Str::limit($banner->link_url, 40) }}
                            </a>
                        @else
                            <small class="text-muted fst-italic">Tidak ada link</small>
                        @endif
                    </div>

                    <div class="card-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-between align-items-center">
                        {{-- Switch Toggle --}}
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-banner cursor-pointer" type="checkbox" data-id="{{ $banner->id }}" {{ $banner->is_active ? 'checked' : '' }} style="transform: scale(1.2);">
                        </div>

                        {{-- Tombol Hapus --}}
                        <button class="btn btn-outline-danger btn-sm rounded-circle delete-banner hover-scale" data-id="{{ $banner->id }}" title="Hapus">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 border border-dashed">
                    <i class="bi bi-images fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted fw-bold">Belum ada banner slider.</h5>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- SECTION 2: PROMO KECIL (Card Grid) --}}
    <div class="animate-fade-in-up delay-200">
        <h5 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-danger">&nbsp; Promo Kecil (3 Kotak Bawah)</h5>
        
        <div class="row g-4">
            @forelse($promos as $promo)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-shadow transition-all group">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $promo->image) }}" class="card-img-top object-fit-cover" height="150" alt="Promo">
                        
                        {{-- Overlay Hapus (Muncul saat Hover) --}}
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center opacity-0 group-hover-opacity transition-all">
                            <a href="{{ route('admin.promo.delete', $promo->id) }}" onclick="return confirm('Hapus promo ini?')" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                                <i class="bi bi-trash me-1"></i> Hapus
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-3 bg-light">
                         @if($promo->link)
                            <a href="{{ $promo->link }}" target="_blank" class="d-block text-truncate text-dark text-decoration-none small">
                                <i class="bi bi-link me-1"></i> Link Tujuan
                            </a>
                        @else
                            <small class="text-muted d-block text-center">Hanya Gambar</small>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-4 bg-white rounded-4 border border-dashed">
                    <small class="text-muted">Belum ada promo kecil.</small>
                </div>
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- MODAL TAMBAH BANNER --}}
<div class="modal fade" id="modalAddBanner" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold">Upload Banner Utama</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formBanner" class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Judul Banner</label>
                        <input type="text" name="title" class="form-control bg-light border-0 p-3" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Link Tujuan (Opsional)</label>
                        <input type="url" name="link_url" class="form-control bg-light border-0 p-3" placeholder="https://...">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Gambar Landscape</label>
                        <input type="file" name="image" class="form-control bg-light border-0 p-3" required>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-dark w-100 py-3 rounded-3 fw-bold">UPLOAD BANNER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PROMO --}}
<div class="modal fade" id="modalAddPromo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white border-0 p-4">
                <h5 class="modal-title fw-bold">Tambah Promo Kecil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.promo.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Link Promo (Opsional)</label>
                        <input type="url" name="link" class="form-control bg-light border-0 p-3">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-muted">Gambar (Rasio 3:1)</label>
                        <input type="file" name="image" class="form-control bg-light border-0 p-3" required>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-danger w-100 py-3 rounded-3 fw-bold">SIMPAN PROMO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .group-hover-opacity:hover { opacity: 1 !important; }
    .group:hover .group-hover-opacity { opacity: 1; }
    .form-check-input:checked { background-color: #198754; border-color: #198754; } /* Hijau untuk aktif */
</style>

@push('scripts')
<script>
    // --- SCRIPT AJAX BANNER (Sama seperti sebelumnya) ---
    $('#formBanner').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('admin.banner.store') }}", type: "POST", data: formData, contentType: false, processData: false,
            success: function(res) {
                $('#modalAddBanner').modal('hide'); $('#formBanner')[0].reset();
                Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Banner berhasil diupload', confirmButtonColor: '#cc002d' });
                setTimeout(() => location.reload(), 1000);
            },
            error: function() { Swal.fire('Error', 'Gagal upload banner', 'error'); }
        });
    });

    $('.toggle-banner').on('change', function() {
        let id = $(this).data('id');
        $.post(`/admin/banner/${id}/toggle`, { _token: '{{ csrf_token() }}' });
    });

    $('.delete-banner').on('click', function() {
        let id = $(this).data('id');
        Swal.fire({ title: 'Hapus Banner?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#cc002d', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, Hapus!' }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({ url: `/admin/banner/${id}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: function() { location.reload(); } });
            }
        });
    });
</script>
@endpush
@endsection