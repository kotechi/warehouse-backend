<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\PenyusutanAset;
use App\Models\RiwayatPemeliharaan;
use App\Models\PenghapusanPemindahtangananAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsetController extends Controller
{
    /**
     * Display a listing of assets
     */
    public function index(Request $request)
    {
        $query = Aset::with([
            'kategoriAset',
            'subkategoriAset',
            'detailKategoriAset',
            'entitas',
            'satker',
            'unitEselonIi',
            'penanggungJawabAset'
        ]);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->has('kategori_aset_id')) {
            $query->where('kategori_aset_id', $request->kategori_aset_id);
        }

        // Filter by kondisi
        if ($request->has('kondisi_fisik')) {
            $query->where('kondisi_fisik', $request->kondisi_fisik);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_aset', 'like', "%{$search}%")
                  ->orWhere('nup', 'like', "%{$search}%");
            });
        }

        $asets = $query->paginate(20);

        return response()->json($asets);
    }

    /**
     * Store a newly created asset
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|unique:asets,kode_barang',
            'nup' => 'nullable|string',
            'kategori_aset_id' => 'required|exists:kategori_asets,id',
            'subkategori_aset_id' => 'nullable|exists:subkategori_asets,id',
            'detail_kategori_aset_id' => 'nullable|exists:detail_kategori_asets,id',
            'nama_aset' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'tanggal_perolehan' => 'nullable|date',
            'nilai_perolehan' => 'required|numeric|min:0',
            'mata_uang' => 'string|default:IDR',
            'sumber_perolehan' => 'required|in:pembelian,hibah,tukar_menukar,penyertaan_modal,hasil_pembangunan,lainnya',
            'keterangan_sumber_perolehan' => 'nullable|string',
            'entitas_id' => 'nullable|exists:entitas,id',
            'satker_id' => 'nullable|exists:satkers,id',
            'unit_eselon_ii_id' => 'nullable|exists:unit_eselon_iis,id',
            'penanggung_jawab_aset_id' => 'nullable|exists:penanggung_jawab_asets,id',
            'unit_pemakai' => 'nullable|string|max:150',
            'kondisi_fisik' => 'required|in:baik,rusak_ringan,rusak_berat',
            'tanggal_mulai_digunakan' => 'nullable|date',
            'umur_manfaat_bulan' => 'nullable|integer|min:1',
            'metode_penyusutan' => 'nullable|in:garis_lurus,saldo_menurun,tidak_disusutkan',
            'nilai_residu' => 'nullable|numeric|min:0',
            'lokasi_fisik' => 'nullable|string|max:255',
            'ruangan' => 'nullable|string|max:100',
            'kode_qr' => 'nullable|string|unique:asets,kode_qr',
            'tag_rfid' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['created_by'] = auth()->id();
            $aset = Aset::create($validated);

            // Auto-generate penyusutan jika aset tetap dan memiliki umur manfaat
            if ($aset->tanggal_mulai_digunakan && $aset->umur_manfaat_bulan) {
                PenyusutanAset::generatePenyusutanBulanan($aset);
            }

            DB::commit();
            return response()->json([
                'message' => 'Aset berhasil ditambahkan',
                'data' => $aset->load([
                    'kategoriAset',
                    'subkategoriAset',
                    'detailKategoriAset'
                ])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menambahkan aset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified asset
     */
    public function show($id)
    {
        $aset = Aset::with([
            'kategoriAset',
            'subkategoriAset',
            'detailKategoriAset',
            'entitas',
            'satker',
            'unitEselonIi',
            'penanggungJawabAset',
            'penyusutanAsets' => function($query) {
                $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->limit(12);
            },
            'riwayatPemeliharaans' => function($query) {
                $query->orderBy('tanggal_pemeliharaan', 'desc');
            },
            'penghapusanPemindahtangananAsets'
        ])->findOrFail($id);

        // Add computed values
        $aset->nilai_buku_terkini = $aset->nilai_buku;
        $aset->akumulasi_penyusutan_terkini = $aset->akumulasi_penyusutan;

        return response()->json($aset);
    }

    /**
     * Update the specified asset
     */
    public function update(Request $request, $id)
    {
        $aset = Aset::findOrFail($id);

        $validated = $request->validate([
            'kode_barang' => 'required|string|unique:asets,kode_barang,' . $id,
            'nup' => 'nullable|string',
            'kategori_aset_id' => 'required|exists:kategori_asets,id',
            'subkategori_aset_id' => 'nullable|exists:subkategori_asets,id',
            'detail_kategori_aset_id' => 'nullable|exists:detail_kategori_asets,id',
            'nama_aset' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'kondisi_fisik' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status' => 'required|in:aktif,dalam_pemeliharaan,rusak,dipindahtangankan,dihapus',
            'lokasi_fisik' => 'nullable|string|max:255',
            'ruangan' => 'nullable|string|max:100',
            'penanggung_jawab_aset_id' => 'nullable|exists:penanggung_jawab_asets,id',
        ]);

        $validated['updated_by'] = auth()->id();
        $aset->update($validated);

        return response()->json([
            'message' => 'Aset berhasil diupdate',
            'data' => $aset
        ]);
    }

    /**
     * Remove the specified asset (soft delete)
     */
    public function destroy($id)
    {
        $aset = Aset::findOrFail($id);
        $aset->delete();

        return response()->json([
            'message' => 'Aset berhasil dihapus'
        ]);
    }

    /**
     * Get asset depreciation history
     */
    public function getPenyusutan($id)
    {
        $aset = Aset::findOrFail($id);
        $penyusutan = $aset->penyusutanAsets()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return response()->json([
            'aset' => [
                'id' => $aset->id,
                'kode_barang' => $aset->kode_barang,
                'nama_aset' => $aset->nama_aset,
                'nilai_perolehan' => $aset->nilai_perolehan,
                'nilai_buku_terkini' => $aset->nilai_buku,
            ],
            'penyusutan' => $penyusutan
        ]);
    }

    /**
     * Add maintenance record
     */
    public function addPemeliharaan(Request $request, $id)
    {
        $aset = Aset::findOrFail($id);

        $validated = $request->validate([
            'tanggal_pemeliharaan' => 'required|date',
            'jenis_pemeliharaan' => 'required|in:preventif,korektif,perbaikan,service,kalibrasi,upgrade,lainnya',
            'deskripsi_pemeliharaan' => 'required|string',
            'kondisi_sebelum' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'kondisi_sesudah' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'biaya' => 'required|numeric|min:0',
            'mata_uang' => 'string|default:IDR',
            'vendor' => 'nullable|string|max:200',
            'kontak_vendor' => 'nullable|string|max:100',
            'lokasi_vendor' => 'nullable|string|max:255',
            'status' => 'required|in:dijadwalkan,sedang_dikerjakan,selesai,dibatalkan',
            'tanggal_selesai' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);

        $validated['aset_id'] = $aset->id;
        $validated['created_by'] = auth()->id();

        $pemeliharaan = RiwayatPemeliharaan::create($validated);

        // Update kondisi aset jika status selesai
        if ($request->status == 'selesai' && $request->kondisi_sesudah) {
            $aset->update([
                'kondisi_fisik' => $request->kondisi_sesudah,
                'status' => 'aktif',
                'updated_by' => auth()->id()
            ]);
        }

        return response()->json([
            'message' => 'Riwayat pemeliharaan berhasil ditambahkan',
            'data' => $pemeliharaan
        ], 201);
    }

    /**
     * Initiate asset disposal or transfer
     */
    public function initiateDisposal(Request $request, $id)
    {
        $aset = Aset::findOrFail($id);

        $validated = $request->validate([
            'jenis_tindakan' => 'required|in:jual,hibah,pindah_tangan,tukar_menukar,penyertaan_modal,pemusnahan,penghapusan',
            'tanggal_pengajuan' => 'required|date',
            'alasan' => 'required|string',
            'kondisi_aset' => 'required|in:baik,rusak_ringan,rusak_berat',
            'nilai_transaksi' => 'nullable|numeric|min:0',
            'pihak_penerima' => 'nullable|string|max:200',
            'alamat_penerima' => 'nullable|string',
            'kontak_penerima' => 'nullable|string|max:100',
            'entitas_tujuan_id' => 'nullable|exists:entitas,id',
            'satker_tujuan_id' => 'nullable|exists:satkers,id',
            'unit_eselon_ii_tujuan_id' => 'nullable|exists:unit_eselon_iis,id',
            'penanggung_jawab_aset_tujuan_id' => 'nullable|exists:penanggung_jawab_asets,id',
        ]);

        $validated['aset_id'] = $aset->id;
        $validated['nilai_buku_saat_ini'] = $aset->nilai_buku;
        $validated['status'] = 'draft';
        $validated['created_by'] = auth()->id();

        $disposal = PenghapusanPemindahtangananAset::create($validated);

        return response()->json([
            'message' => 'Pengajuan penghapusan/pemindahtanganan berhasil dibuat',
            'data' => $disposal
        ], 201);
    }

    /**
     * Get asset statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total_aset' => Aset::count(),
            'total_nilai_perolehan' => Aset::sum('nilai_perolehan'),
            'by_status' => Aset::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'by_kondisi' => Aset::select('kondisi_fisik', DB::raw('count(*) as total'))
                ->groupBy('kondisi_fisik')
                ->get(),
            'by_kategori' => Aset::select('kategori_aset_id', DB::raw('count(*) as total'))
                ->with('kategoriAset:id,nama_kategori')
                ->groupBy('kategori_aset_id')
                ->get(),
            'perlu_pemeliharaan' => Aset::where('kondisi_fisik', 'rusak_ringan')
                ->orWhere('kondisi_fisik', 'rusak_berat')
                ->count(),
        ];

        return response()->json($stats);
    }
}
