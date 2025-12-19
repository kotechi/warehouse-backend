<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\KategoriAset;
use App\Models\SubkategoriAset;
use App\Models\DetailKategoriAset;
use App\Models\Entitas;
use App\Models\Satker;
use App\Models\UnitEselonIi;
use App\Models\PenanggungJawabAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DropdownController extends Controller
{
    /**
     * Get Kategori Aset dropdown with search
     */
    public function kategoriAset(Request $request)
    {
        $search = $request->input('search', '');
        
        $data = KategoriAset::where('status', 'aktif')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kategori', 'LIKE', "%{$search}%")
                      ->orWhere('kode_kategori', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('nama_kategori')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->nama_kategori . ' (' . $item->kode_kategori . ')',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get Subkategori Aset dropdown with search
     */
    public function subkategoriAset(Request $request)
    {
        $search = $request->input('search', '');
        $kategoriAsetId = $request->input('kategori_aset_id');
        
        $data = SubkategoriAset::where('status', 'aktif')
            ->when($kategoriAsetId, function ($query) use ($kategoriAsetId) {
                $query->where('kategori_aset_id', $kategoriAsetId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_subkategori', 'LIKE', "%{$search}%")
                      ->orWhere('kode_subkategori', 'LIKE', "%{$search}%");
                });
            })
            ->with('kategoriAset:id,nama_kategori')
            ->orderBy('nama_subkategori')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->nama_subkategori . ' (' . $item->kode_subkategori . ')',
                    'kategori_aset_id' => $item->kategori_aset_id,
                    'kategori_nama' => $item->kategoriAset->nama_kategori ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get Detail Kategori Aset dropdown with search
     */
    public function detailKategoriAset(Request $request)
    {
        $search = $request->input('search', '');
        $subkategoriAsetId = $request->input('subkategori_aset_id');
        
        $data = DetailKategoriAset::where('status', 'aktif')
            ->when($subkategoriAsetId, function ($query) use ($subkategoriAsetId) {
                $query->where('subkategori_aset_id', $subkategoriAsetId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_detail_kategori', 'LIKE', "%{$search}%")
                      ->orWhere('kode_detail_kategori', 'LIKE', "%{$search}%");
                });
            })
            ->with('subkategoriAset:id,nama_subkategori')
            ->orderBy('nama_detail_kategori')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->nama_detail_kategori . ' (' . $item->kode_detail_kategori . ')',
                    'subkategori_aset_id' => $item->subkategori_aset_id,
                    'subkategori_nama' => $item->subkategoriAset->nama_subkategori ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get Entitas dropdown with search
     */
    public function entitas(Request $request)
    {
        $search = $request->input('search', '');
        
        $data = Entitas::where('status', 'aktif')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_entitas', 'LIKE', "%{$search}%")
                      ->orWhere('kode_entitas', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('nama_entitas')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->nama_entitas . ' (' . $item->kode_entitas . ')',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get Satker dropdown with search
     */
    public function satker(Request $request)
    {
        $search = $request->input('search', '');
        $entitasId = $request->input('entitas_id');
        
        $data = Satker::where('status', 'aktif')
            ->when($entitasId, function ($query) use ($entitasId) {
                $query->where('entitas_id', $entitasId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_satker', 'LIKE', "%{$search}%")
                      ->orWhere('kode_satker', 'LIKE', "%{$search}%");
                });
            })
            ->with('entitas:id,nama_entitas')
            ->orderBy('nama_satker')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->nama_satker . ' (' . $item->kode_satker . ')',
                    'entitas_id' => $item->entitas_id,
                    'entitas_nama' => $item->entitas->nama_entitas ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get Unit Eselon II dropdown with search
     */
    public function unitEselonIi(Request $request)
    {
        $search = $request->input('search', '');
        $satkerId = $request->input('satker_id');
        
        $data = UnitEselonIi::where('status', 'aktif')
            ->when($satkerId, function ($query) use ($satkerId) {
                $query->where('satker_id', $satkerId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_unit', 'LIKE', "%{$search}%")
                      ->orWhere('kode_unit', 'LIKE', "%{$search}%");
                });
            })
            ->with('satker:id,nama_satker')
            ->orderBy('nama_unit')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->nama_unit . ' (' . $item->kode_unit . ')',
                    'satker_id' => $item->satker_id,
                    'satker_nama' => $item->satker->nama_satker ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get Penanggung Jawab Aset dropdown with search
     */
    public function penanggungJawabAset(Request $request)
    {
        $search = $request->input('search', '');
        $unitEselonIiId = $request->input('unit_eselon_ii_id');
        
        $data = PenanggungJawabAset::where('status', 'aktif')
            ->when($unitEselonIiId, function ($query) use ($unitEselonIiId) {
                $query->where('unit_eselon_ii_id', $unitEselonIiId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_pic', 'LIKE', "%{$search}%")
                      ->orWhere('nip', 'LIKE', "%{$search}%")
                      ->orWhere('jabatan', 'LIKE', "%{$search}%");
                });
            })
            ->with('unitEselonIi:id,nama_unit')
            ->orderBy('nama_pic')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->nama_pic . ' - ' . $item->jabatan . ' (' . $item->nip . ')',
                    'unit_eselon_ii_id' => $item->unit_eselon_ii_id,
                    'unit_nama' => $item->unitEselonIi->nama_unit ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get Mata Uang dropdown (static options)
     */
    public function mataUang(Request $request)
    {
        $search = $request->input('search', '');
        
        $currencies = [
            ['value' => 'IDR', 'label' => 'IDR - Indonesian Rupiah'],
            ['value' => 'USD', 'label' => 'USD - United States Dollar'],
            ['value' => 'EUR', 'label' => 'EUR - Euro'],
            ['value' => 'GBP', 'label' => 'GBP - British Pound Sterling'],
            ['value' => 'JPY', 'label' => 'JPY - Japanese Yen'],
            ['value' => 'SGD', 'label' => 'SGD - Singapore Dollar'],
            ['value' => 'MYR', 'label' => 'MYR - Malaysian Ringgit'],
        ];

        if ($search) {
            $currencies = array_filter($currencies, function ($currency) use ($search) {
                return stripos($currency['label'], $search) !== false || 
                       stripos($currency['value'], $search) !== false;
            });
        }

        return response()->json([
            'success' => true,
            'data' => array_values($currencies),
        ]);
    }

    /**
     * Get Kondisi Fisik dropdown (static options)
     */
    public function kondisiFisik(Request $request)
    {
        $search = $request->input('search', '');
        
        $conditions = [
            ['value' => 'Baik', 'label' => 'Baik'],
            ['value' => 'Rusak Ringan', 'label' => 'Rusak Ringan'],
            ['value' => 'Rusak Sedang', 'label' => 'Rusak Sedang'],
            ['value' => 'Rusak Berat', 'label' => 'Rusak Berat'],
        ];

        if ($search) {
            $conditions = array_filter($conditions, function ($condition) use ($search) {
                return stripos($condition['label'], $search) !== false;
            });
        }

        return response()->json([
            'success' => true,
            'data' => array_values($conditions),
        ]);
    }

    /**
     * Get Status Aset dropdown (static options)
     */
    public function statusAset(Request $request)
    {
        $search = $request->input('search', '');
        
        $statuses = [
            ['value' => 'Aktif', 'label' => 'Aktif'],
            ['value' => 'Dalam Perbaikan', 'label' => 'Dalam Perbaikan'],
            ['value' => 'Tidak Digunakan', 'label' => 'Tidak Digunakan'],
            ['value' => 'Dihapuskan', 'label' => 'Dihapuskan'],
            ['value' => 'Dipindahtangankan', 'label' => 'Dipindahtangankan'],
        ];

        if ($search) {
            $statuses = array_filter($statuses, function ($status) use ($search) {
                return stripos($status['label'], $search) !== false;
            });
        }

        return response()->json([
            'success' => true,
            'data' => array_values($statuses),
        ]);
    }

    /**
     * Get Metode Penyusutan dropdown (static options)
     */
    public function metodePenyusutan(Request $request)
    {
        $search = $request->input('search', '');
        
        $methods = [
            ['value' => 'Garis Lurus', 'label' => 'Garis Lurus'],
            ['value' => 'Saldo Menurun', 'label' => 'Saldo Menurun'],
            ['value' => 'Tidak Disusutkan', 'label' => 'Tidak Disusutkan'],
        ];

        if ($search) {
            $methods = array_filter($methods, function ($method) use ($search) {
                return stripos($method['label'], $search) !== false;
            });
        }

        return response()->json([
            'success' => true,
            'data' => array_values($methods),
        ]);
    }

    /**
     * Get Satuan dropdown (static options)
     */
    public function satuan(Request $request)
    {
        $search = $request->input('search', '');
        
        $units = [
            ['value' => 'Unit', 'label' => 'Unit'],
            ['value' => 'Set', 'label' => 'Set'],
            ['value' => 'Buah', 'label' => 'Buah'],
            ['value' => 'Lembar', 'label' => 'Lembar'],
            ['value' => 'Meter', 'label' => 'Meter'],
            ['value' => 'Meter Persegi', 'label' => 'Meter Persegi'],
            ['value' => 'Kilogram', 'label' => 'Kilogram'],
            ['value' => 'Paket', 'label' => 'Paket'],
        ];

        if ($search) {
            $units = array_filter($units, function ($unit) use ($search) {
                return stripos($unit['label'], $search) !== false;
            });
        }

        return response()->json([
            'success' => true,
            'data' => array_values($units),
        ]);
    }
}
