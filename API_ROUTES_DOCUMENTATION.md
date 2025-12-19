# API Routes untuk Sistem Manajemen Aset

Tambahkan routes berikut ke file `routes/api.php`:

```php
use App\Http\Controllers\AsetController;
use App\Http\Controllers\Api\V1\DropdownController;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
    
    // Dropdown Routes for Aset CRUD
    Route::prefix('dropdown')->group(function () {
        Route::get('kategori-aset', [DropdownController::class, 'kategoriAset']);
        Route::get('subkategori-aset', [DropdownController::class, 'subkategoriAset']);
        Route::get('detail-kategori-aset', [DropdownController::class, 'detailKategoriAset']);
        Route::get('entitas', [DropdownController::class, 'entitas']);
        Route::get('satker', [DropdownController::class, 'satker']);
        Route::get('unit-eselon-ii', [DropdownController::class, 'unitEselonIi']);
        Route::get('penanggung-jawab-aset', [DropdownController::class, 'penanggungJawabAset']);
        Route::get('mata-uang', [DropdownController::class, 'mataUang']);
        Route::get('kondisi-fisik', [DropdownController::class, 'kondisiFisik']);
        Route::get('status-aset', [DropdownController::class, 'statusAset']);
        Route::get('metode-penyusutan', [DropdownController::class, 'metodePenyusutan']);
        Route::get('satuan', [DropdownController::class, 'satuan']);
    });
    
    // Asset Management Routes
    Route::prefix('aset')->group(function () {
        // CRUD Aset
        Route::get('/', [AsetController::class, 'index']); // List all assets with filters
        Route::post('/', [AsetController::class, 'store']); // Create new asset
        Route::get('/{id}', [AsetController::class, 'show']); // Get asset detail
        Route::put('/{id}', [AsetController::class, 'update']); // Update asset
        Route::delete('/{id}', [AsetController::class, 'destroy']); // Soft delete asset
        
        // Penyusutan
        Route::get('/{id}/penyusutan', [AsetController::class, 'getPenyusutan']); // Get depreciation history
        
        // Pemeliharaan
        Route::post('/{id}/pemeliharaan', [AsetController::class, 'addPemeliharaan']); // Add maintenance record
        
        // Penghapusan/Pemindahtanganan
        Route::post('/{id}/disposal', [AsetController::class, 'initiateDisposal']); // Initiate disposal/transfer
        
        // Statistics
        Route::get('/statistics/summary', [AsetController::class, 'getStatistics']); // Get asset statistics
    });
});
```

## Contoh Request/Response

## DROPDOWN API ENDPOINTS

Semua endpoint dropdown API menggunakan prefix `/api/v1/dropdown` dan memerlukan authentication token (Bearer Token).

### Format Response Dropdown
Semua endpoint dropdown mengembalikan response dengan format berikut:
```json
{
  "success": true,
  "data": [
    {
      "value": 1,
      "label": "Nama Item (Kode)"
    }
  ]
}
```

### Query Parameters (Berlaku untuk semua endpoint dropdown)
- `search`: String untuk mencari item (case-insensitive)
- Parameter tambahan sesuai dengan relasi (dijelaskan di setiap endpoint)

---

### 1. Dropdown Kategori Aset
**Endpoint:** `GET /api/v1/dropdown/kategori-aset`

**Query Parameters:**
- `search`: Cari berdasarkan nama atau kode kategori

**Contoh Request:**
```
GET /api/v1/dropdown/kategori-aset?search=tetap
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": 1,
      "label": "Aset Tetap (AT)"
    },
    {
      "value": 2,
      "label": "Aset Bergerak Tetap (ABT)"
    }
  ]
}
```

---

### 2. Dropdown Subkategori Aset
**Endpoint:** `GET /api/v1/dropdown/subkategori-aset`

**Query Parameters:**
- `search`: Cari berdasarkan nama atau kode subkategori
- `kategori_aset_id`: Filter berdasarkan kategori aset (optional)

**Contoh Request:**
```
GET /api/v1/dropdown/subkategori-aset?kategori_aset_id=1&search=peralatan
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": 1,
      "label": "Peralatan Kantor (PK)",
      "kategori_aset_id": 1,
      "kategori_nama": "Aset Tetap"
    },
    {
      "value": 2,
      "label": "Peralatan Komputer (PC)",
      "kategori_aset_id": 1,
      "kategori_nama": "Aset Tetap"
    }
  ]
}
```

---

### 3. Dropdown Detail Kategori Aset
**Endpoint:** `GET /api/v1/dropdown/detail-kategori-aset`

**Query Parameters:**
- `search`: Cari berdasarkan nama atau kode detail kategori
- `subkategori_aset_id`: Filter berdasarkan subkategori aset (optional)

**Contoh Request:**
```
GET /api/v1/dropdown/detail-kategori-aset?subkategori_aset_id=2&search=laptop
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": 1,
      "label": "Laptop (LT)",
      "subkategori_aset_id": 2,
      "subkategori_nama": "Peralatan Komputer"
    },
    {
      "value": 2,
      "label": "Desktop (DT)",
      "subkategori_aset_id": 2,
      "subkategori_nama": "Peralatan Komputer"
    }
  ]
}
```

---

### 4. Dropdown Entitas
**Endpoint:** `GET /api/v1/dropdown/entitas`

**Query Parameters:**
- `search`: Cari berdasarkan nama atau kode entitas

**Contoh Request:**
```
GET /api/v1/dropdown/entitas?search=kementerian
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": 1,
      "label": "Kementerian Keuangan (KEMENKEU)"
    },
    {
      "value": 2,
      "label": "Kementerian Dalam Negeri (KEMENDAGRI)"
    }
  ]
}
```

---

### 5. Dropdown Satker
**Endpoint:** `GET /api/v1/dropdown/satker`

**Query Parameters:**
- `search`: Cari berdasarkan nama atau kode satker
- `entitas_id`: Filter berdasarkan entitas (optional)

**Contoh Request:**
```
GET /api/v1/dropdown/satker?entitas_id=1&search=jakarta
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": 1,
      "label": "Kantor Wilayah DKI Jakarta (KW-DKI)",
      "entitas_id": 1,
      "entitas_nama": "Kementerian Keuangan"
    }
  ]
}
```

---

### 6. Dropdown Unit Eselon II
**Endpoint:** `GET /api/v1/dropdown/unit-eselon-ii`

**Query Parameters:**
- `search`: Cari berdasarkan nama atau kode unit
- `satker_id`: Filter berdasarkan satker (optional)

**Contoh Request:**
```
GET /api/v1/dropdown/unit-eselon-ii?satker_id=1&search=keuangan
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": 1,
      "label": "Bagian Keuangan (BK)",
      "satker_id": 1,
      "satker_nama": "Kantor Wilayah DKI Jakarta"
    }
  ]
}
```

---

### 7. Dropdown Penanggung Jawab Aset
**Endpoint:** `GET /api/v1/dropdown/penanggung-jawab-aset`

**Query Parameters:**
- `search`: Cari berdasarkan nama PIC, NIP, atau jabatan
- `unit_eselon_ii_id`: Filter berdasarkan unit eselon II (optional)

**Contoh Request:**
```
GET /api/v1/dropdown/penanggung-jawab-aset?unit_eselon_ii_id=1&search=budi
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": 1,
      "label": "Budi Santoso - Kepala Bagian (198501012010011001)",
      "unit_eselon_ii_id": 1,
      "unit_nama": "Bagian Keuangan"
    }
  ]
}
```

---

### 8. Dropdown Mata Uang
**Endpoint:** `GET /api/v1/dropdown/mata-uang`

**Query Parameters:**
- `search`: Cari berdasarkan kode atau nama mata uang

**Contoh Request:**
```
GET /api/v1/dropdown/mata-uang?search=idr
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": "IDR",
      "label": "IDR - Indonesian Rupiah"
    },
    {
      "value": "USD",
      "label": "USD - United States Dollar"
    },
    {
      "value": "EUR",
      "label": "EUR - Euro"
    }
  ]
}
```

---

### 9. Dropdown Kondisi Fisik
**Endpoint:** `GET /api/v1/dropdown/kondisi-fisik`

**Query Parameters:**
- `search`: Cari berdasarkan nama kondisi

**Contoh Request:**
```
GET /api/v1/dropdown/kondisi-fisik
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": "Baik",
      "label": "Baik"
    },
    {
      "value": "Rusak Ringan",
      "label": "Rusak Ringan"
    },
    {
      "value": "Rusak Sedang",
      "label": "Rusak Sedang"
    },
    {
      "value": "Rusak Berat",
      "label": "Rusak Berat"
    }
  ]
}
```

---

### 10. Dropdown Status Aset
**Endpoint:** `GET /api/v1/dropdown/status-aset`

**Query Parameters:**
- `search`: Cari berdasarkan nama status

**Contoh Request:**
```
GET /api/v1/dropdown/status-aset
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": "Aktif",
      "label": "Aktif"
    },
    {
      "value": "Dalam Perbaikan",
      "label": "Dalam Perbaikan"
    },
    {
      "value": "Tidak Digunakan",
      "label": "Tidak Digunakan"
    },
    {
      "value": "Dihapuskan",
      "label": "Dihapuskan"
    },
    {
      "value": "Dipindahtangankan",
      "label": "Dipindahtangankan"
    }
  ]
}
```

---

### 11. Dropdown Metode Penyusutan
**Endpoint:** `GET /api/v1/dropdown/metode-penyusutan`

**Query Parameters:**
- `search`: Cari berdasarkan nama metode

**Contoh Request:**
```
GET /api/v1/dropdown/metode-penyusutan
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": "Garis Lurus",
      "label": "Garis Lurus"
    },
    {
      "value": "Saldo Menurun",
      "label": "Saldo Menurun"
    },
    {
      "value": "Tidak Disusutkan",
      "label": "Tidak Disusutkan"
    }
  ]
}
```

---

### 12. Dropdown Satuan
**Endpoint:** `GET /api/v1/dropdown/satuan`

**Query Parameters:**
- `search`: Cari berdasarkan nama satuan

**Contoh Request:**
```
GET /api/v1/dropdown/satuan
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": "Unit",
      "label": "Unit"
    },
    {
      "value": "Set",
      "label": "Set"
    },
    {
      "value": "Buah",
      "label": "Buah"
    },
    {
      "value": "Lembar",
      "label": "Lembar"
    }
  ]
}
```

---

## CONTOH PENGGUNAAN DROPDOWN DALAM FORM ASET

### Contoh Integrasi dengan Frontend (React/Vue/Angular)

```javascript
// Contoh fetch dropdown dengan search
const fetchKategoriAset = async (searchTerm = '') => {
  const response = await fetch(
    `/api/v1/dropdown/kategori-aset?search=${searchTerm}`,
    {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    }
  );
  const result = await response.json();
  return result.data; // [{value: 1, label: "..."}, ...]
};

// Contoh cascade dropdown (kategori -> subkategori -> detail)
const handleKategoriChange = async (kategoriId) => {
  const subkategoriData = await fetch(
    `/api/v1/dropdown/subkategori-aset?kategori_aset_id=${kategoriId}`,
    {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    }
  );
  // Update subkategori dropdown options
};
```

---

## ASSET CRUD API ENDPOINTS

### 1. List Assets (GET /api/v1/aset)
**Query Parameters:**
- `status`: Filter by status (aktif, dalam_pemeliharaan, rusak, dipindahtangankan, dihapus)
- `kategori_aset_id`: Filter by kategori
- `kondisi_fisik`: Filter by kondisi (baik, rusak_ringan, rusak_berat)
- `search`: Search by kode_barang, nama_aset, or nup
- `page`: Page number
- `per_page`: Items per page

**Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "kode_barang": "BMN-2025-001",
      "nup": "001/2025",
      "nama_aset": "Laptop Lenovo ThinkPad X1 Carbon",
      "kategori_aset": {
        "id": 2,
        "nama_kategori": "Aset Tetap"
      },
      "nilai_perolehan": "12000000.00",
      "kondisi_fisik": "baik",
      "status": "aktif"
    }
  ],
  "total": 100
}
```

### 2. Create Asset (POST /api/v1/aset)
**Request Body:**
```json
{
  "kode_barang": "BMN-2025-001",
  "nup": "001/2025",
  "kategori_aset_id": 2,
  "subkategori_aset_id": 2,
  "detail_kategori_aset_id": 1,
  "nama_aset": "Laptop Lenovo ThinkPad X1 Carbon",
  "spesifikasi": "Intel Core i7-12th Gen, RAM 16GB, SSD 512GB, Serial: ABC123456",
  "jumlah": 1,
  "satuan": "unit",
  "tanggal_perolehan": "2025-01-15",
  "nilai_perolehan": 12000000,
  "mata_uang": "IDR",
  "sumber_perolehan": "pembelian",
  "entitas_id": 1,
  "satker_id": 1,
  "unit_eselon_ii_id": 1,
  "penanggung_jawab_aset_id": 1,
  "kondisi_fisik": "baik",
  "tanggal_mulai_digunakan": "2025-01-20",
  "umur_manfaat_bulan": 48,
  "metode_penyusutan": "garis_lurus",
  "nilai_residu": 0,
  "lokasi_fisik": "Kantor Pusat",
  "ruangan": "Ruang IT"
}
```

**Response:**
```json
{
  "message": "Aset berhasil ditambahkan",
  "data": {
    "id": 1,
    "kode_barang": "BMN-2025-001",
    "nama_aset": "Laptop Lenovo ThinkPad X1 Carbon",
    "kategori_aset": {
      "id": 2,
      "nama_kategori": "Aset Tetap"
    }
  }
}
```

### 3. Get Asset Detail (GET /api/v1/aset/{id})
**Response:**
```json
{
  "id": 1,
  "kode_barang": "BMN-2025-001",
  "nup": "001/2025",
  "nama_aset": "Laptop Lenovo ThinkPad X1 Carbon",
  "spesifikasi": "Intel Core i7-12th Gen, RAM 16GB, SSD 512GB",
  "nilai_perolehan": "12000000.00",
  "nilai_buku_terkini": "11000000.00",
  "akumulasi_penyusutan_terkini": "1000000.00",
  "kategori_aset": {
    "id": 2,
    "nama_kategori": "Aset Tetap"
  },
  "penyusutan_asets": [
    {
      "tahun": 2025,
      "bulan": 4,
      "penyusutan_per_bulan": "250000.00",
      "akumulasi_penyusutan": "1000000.00",
      "nilai_buku": "11000000.00"
    }
  ],
  "riwayat_pemeliharaans": [
    {
      "tanggal_pemeliharaan": "2025-12-06",
      "jenis_pemeliharaan": "service",
      "deskripsi_pemeliharaan": "Service SSD Laptop",
      "biaya": "300000.00",
      "vendor": "Jambu Dua"
    }
  ]
}
```

### 4. Add Maintenance (POST /api/v1/aset/{id}/pemeliharaan)
**Request Body:**
```json
{
  "tanggal_pemeliharaan": "2025-12-06",
  "jenis_pemeliharaan": "service",
  "deskripsi_pemeliharaan": "Service SSD Laptop - Replace thermal paste and clean fan",
  "kondisi_sebelum": "rusak_ringan",
  "kondisi_sesudah": "baik",
  "biaya": 300000,
  "mata_uang": "IDR",
  "vendor": "Jambu Dua",
  "status": "selesai",
  "tanggal_selesai": "2025-12-06"
}
```

**Response:**
```json
{
  "message": "Riwayat pemeliharaan berhasil ditambahkan",
  "data": {
    "id": 1,
    "aset_id": 1,
    "tanggal_pemeliharaan": "2025-12-06",
    "jenis_pemeliharaan": "service",
    "biaya": "300000.00"
  }
}
```

### 5. Initiate Disposal/Transfer (POST /api/v1/aset/{id}/disposal)
**Request Body (Pindah Tangan):**
```json
{
  "jenis_tindakan": "pindah_tangan",
  "tanggal_pengajuan": "2025-12-10",
  "alasan": "Pemindahan aset ke unit cabang",
  "kondisi_aset": "baik",
  "entitas_tujuan_id": 1,
  "satker_tujuan_id": 2,
  "unit_eselon_ii_tujuan_id": 3,
  "penanggung_jawab_aset_tujuan_id": 5
}
```

**Response:**
```json
{
  "message": "Pengajuan penghapusan/pemindahtanganan berhasil dibuat",
  "data": {
    "id": 1,
    "aset_id": 1,
    "jenis_tindakan": "pindah_tangan",
    "status": "draft",
    "nilai_buku_saat_ini": "11000000.00"
  }
}
```

### 6. Get Statistics (GET /api/v1/aset/statistics/summary)
**Response:**
```json
{
  "total_aset": 150,
  "total_nilai_perolehan": "5000000000.00",
  "by_status": [
    {
      "status": "aktif",
      "total": 130
    },
    {
      "status": "rusak",
      "total": 15
    }
  ],
  "by_kondisi": [
    {
      "kondisi_fisik": "baik",
      "total": 120
    },
    {
      "kondisi_fisik": "rusak_ringan",
      "total": 25
    }
  ],
  "perlu_pemeliharaan": 30
}
```

## Authentication
Semua endpoint memerlukan authentication menggunakan Sanctum token.

**Header:**
```
Authorization: Bearer {token}
```

## Error Responses
**400 Bad Request:**
```json
{
  "message": "Validation failed",
  "errors": {
    "kode_barang": ["The kode barang has already been taken."]
  }
}
```

**404 Not Found:**
```json
{
  "message": "Aset tidak ditemukan"
}
```

**500 Server Error:**
```json
{
  "message": "Gagal menambahkan aset",
  "error": "Error details"
}
```
