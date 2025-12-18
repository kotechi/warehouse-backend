# API Routes untuk Sistem Manajemen Aset

Tambahkan routes berikut ke file `routes/api.php`:

```php
use App\Http\Controllers\AsetController;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
    
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
