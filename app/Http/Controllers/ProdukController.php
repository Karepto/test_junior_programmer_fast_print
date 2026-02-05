<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Status;
use App\Services\ApiService;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    protected ApiService $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Tampilkan semua produk
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = Produk::with(['kategori', 'status']);
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'ilike', '%' . $search . '%')
                  ->orWhereHas('kategori', function ($q2) use ($search) {
                      $q2->where('nama_kategori', 'ilike', '%' . $search . '%');
                  });
            });
        }
        
        $produks = $query->get();
        
        return view('produk.index', compact('produks', 'search'));
    }

    /**
     * Tampilkan produk yang bisa dijual saja
     */
    public function bisaDijual(Request $request)
    {
        $search = $request->query('search');
        
        $query = Produk::with(['kategori', 'status'])
            ->whereHas('status', function ($query) {
                $query->where('nama_status', 'bisa dijual');
            });
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'ilike', '%' . $search . '%')
                  ->orWhereHas('kategori', function ($q2) use ($search) {
                      $q2->where('nama_kategori', 'ilike', '%' . $search . '%');
                  });
            });
        }
        
        $produks = $query->get();
        
        return view('produk.bisa-dijual', compact('produks', 'search'));
    }

    /**
     * Tampilkan form untuk membuat produk baru
     */
    public function create(Request $request)
    {
        $kategoris = Kategori::all();
        $statuses = Status::all();
        $from = $request->query('from', 'index');
        
        return view('produk.create', compact('kategoris', 'statuses', 'from'));
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategori,id_kategori',
            'status_id' => 'required|exists:status,id_status',
        ], [
            'nama_produk.required' => 'Nama produk harus diisi.',
            'harga.required' => 'Harga harus diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'kategori_id.required' => 'Kategori harus dipilih.',
            'status_id.required' => 'Status harus dipilih.',
        ]);

        Produk::create($validated);

        $redirectRoute = $request->input('from') === 'bisa-dijual' ? 'produk.bisa-dijual' : 'produk.index';
        return redirect()->route($redirectRoute)->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk edit produk
     */
    public function edit(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $kategoris = Kategori::all();
        $statuses = Status::all();
        $from = $request->query('from', 'index');
        
        return view('produk.edit', compact('produk', 'kategoris', 'statuses', 'from'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategori,id_kategori',
            'status_id' => 'required|exists:status,id_status',
        ], [
            'nama_produk.required' => 'Nama produk harus diisi.',
            'harga.required' => 'Harga harus diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'kategori_id.required' => 'Kategori harus dipilih.',
            'status_id.required' => 'Status harus dipilih.',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($validated);

        $redirectRoute = $request->input('from') === 'bisa-dijual' ? 'produk.bisa-dijual' : 'produk.index';
        return redirect()->route($redirectRoute)->with('success', 'Produk berhasil diupdate.');
    }

    /**
     * Hapus produk
     */
    public function destroy(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        $redirectRoute = $request->input('from') === 'bisa-dijual' ? 'produk.bisa-dijual' : 'produk.index';
        return redirect()->route($redirectRoute)->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Sync data dari API ke database
     */
    public function syncFromApi()
    {
        $result = $this->apiService->fetchProduk();

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message'] ?? 'Gagal mengambil data dari API');
        }

        $dataApi = $result['data'];
        $imported = 0;

        foreach ($dataApi as $item) {
            // Cari atau buat kategori
            $kategori = Kategori::firstOrCreate(
                ['nama_kategori' => $item['kategori'] ?? 'Uncategorized']
            );

            // Cari atau buat status
            $status = Status::firstOrCreate(
                ['nama_status' => strtolower($item['status'] ?? 'unknown')]
            );

            // Cari atau update produk
            Produk::updateOrCreate(
                ['nama_produk' => $item['nama_produk']],
                [
                    'harga' => $item['harga'] ?? 0,
                    'kategori_id' => $kategori->id_kategori,
                    'status_id' => $status->id_status,
                ]
            );

            $imported++;
        }

        return redirect()->back()->with('success', "Berhasil mengimport {$imported} produk dari API");
    }
}
