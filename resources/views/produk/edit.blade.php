@extends('layouts.app')

@section('title', 'Edit Produk - Fast Print')
@section('page-title', 'Edit Produk')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Form Edit Produk</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST"
                    id="formProduk">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="from" value="{{ $from ?? 'index' }}">

                    <div class="mb-3">
                        <label for="nama_produk" class="form-label">Nama Produk <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                            id="nama_produk" name="nama_produk"
                            value="{{ old('nama_produk', $produk->nama_produk) }}"
                            placeholder="Masukkan nama produk" required>
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback" id="nama_produk_error">Nama produk harus diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga"
                                name="harga" value="{{ old('harga', $produk->harga) }}"
                                placeholder="0" min="0" step="1" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="harga_error">Harga harus berupa angka.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id"
                            name="kategori_id">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}"
                                    {{ old('kategori_id', $produk->kategori_id) == $kategori->id_kategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status_id" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status_id') is-invalid @enderror" id="status_id"
                            name="status_id">
                            <option value="">-- Pilih Status --</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id_status }}"
                                    {{ old('status_id', $produk->status_id) == $status->id_status ? 'selected' : '' }}>
                                    {{ $status->nama_status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                        <a href="{{ ($from ?? 'index') === 'bisa-dijual' ? route('produk.bisa-dijual') : route('produk.index') }}"
                            class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.getElementById('formProduk').addEventListener('submit', function (e) {
            let isValid = true;
            const namaProduk = document.getElementById('nama_produk');
            const harga = document.getElementById('harga');
            const namaProdukError = document.getElementById('nama_produk_error');
            const hargaError = document.getElementById('harga_error');

            // Reset validation
            namaProduk.classList.remove('is-invalid');
            harga.classList.remove('is-invalid');

            // Validate nama produk
            if (!namaProduk.value.trim()) {
                namaProduk.classList.add('is-invalid');
                namaProdukError.style.display = 'block';
                isValid = false;
            }

            // Validate harga (must be numeric)
            if (!harga.value || isNaN(harga.value) || parseFloat(harga.value) < 0) {
                harga.classList.add('is-invalid');
                hargaError.style.display = 'block';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                alert(
                    'Mohon lengkapi form dengan benar:\n- Nama produk harus diisi\n- Harga harus berupa angka');
            }
        });

    </script>
@endpush
