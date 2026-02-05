@extends('layouts.app')

@section('title', 'Produk Bisa Dijual - Fast Print')
@section('page-title', 'Produk Bisa Dijual')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-0">Daftar Produk yang Bisa Dijual</h6>
                        <span class="badge bg-gradient-success mt-2" style="font-size: 0.75rem;">Total:
                            {{ $produks->count() }} Produk</span>
                    </div>
                    <a href="{{ route('produk.create', ['from' => 'bisa-dijual']) }}"
                        class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Produk
                    </a>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <form action="{{ route('produk.bisa-dijual') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                    placeholder="Cari produk atau kategori..."
                                    value="{{ $search ?? '' }}">
                                <button class="btn btn-primary mb-0" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($search)
                                    <a href="{{ route('produk.bisa-dijual') }}"
                                        class="btn btn-secondary mb-0">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-3 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Nama Produk</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Harga</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Kategori</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $index => $produk)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <span
                                                class="text-secondary text-xs font-weight-bold">{{ $index + 1 }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $produk->nama_produk }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Rp
                                            {{ number_format($produk->harga, 0, ',', '.') }}
                                        </p>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-gradient-info">{{ $produk->kategori->nama_kategori ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span
                                            class="badge bg-gradient-success">{{ $produk->status->nama_status }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('produk.edit', ['id' => $produk->id_produk, 'from' => 'bisa-dijual']) }}"
                                            class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete"
                                            data-id="{{ $produk->id_produk }}"
                                            data-nama="{{ $produk->nama_produk }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $produk->id_produk }}"
                                            action="{{ route('produk.destroy', $produk->id_produk) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="from" value="bisa-dijual">
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-secondary mb-0">Tidak ada produk dengan status "bisa dijual".</p>
                                        <p class="text-xs text-muted">Klik "Sync dari API" untuk mengambil data.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');

                if (confirm('Apakah Anda yakin ingin menghapus produk "' + nama + '"?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });

    </script>
@endpush
