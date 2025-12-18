<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailKategoriAsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID subkategori Peralatan dan Mesin
        $peralatanMesinId = DB::table('subkategori_asets')->where('kode_subkategori', 'PERALATAN_MESIN')->value('id');
        $asetTetapLainnyaId = DB::table('subkategori_asets')->where('kode_subkategori', 'ASET_TETAP_LAINNYA')->value('id');

        $detailKategoris = [
            // Detail untuk Peralatan dan Mesin
            [
                'subkategori_aset_id' => $peralatanMesinId,
                'kode_detail_kategori' => 'PC_LAPTOP',
                'nama_detail_kategori' => 'PC/Laptop',
                'deskripsi' => 'Komputer PC, Laptop, All-in-One',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subkategori_aset_id' => $peralatanMesinId,
                'kode_detail_kategori' => 'PRINTER',
                'nama_detail_kategori' => 'Printer',
                'deskripsi' => 'Printer, Scanner, Mesin Fotocopy',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subkategori_aset_id' => $peralatanMesinId,
                'kode_detail_kategori' => 'KAMERA',
                'nama_detail_kategori' => 'Kamera',
                'deskripsi' => 'Kamera Digital, DSLR, Video Camera',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subkategori_aset_id' => $peralatanMesinId,
                'kode_detail_kategori' => 'PROYEKTOR',
                'nama_detail_kategori' => 'Proyektor',
                'deskripsi' => 'LCD Proyektor, LED Proyektor',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subkategori_aset_id' => $peralatanMesinId,
                'kode_detail_kategori' => 'AC',
                'nama_detail_kategori' => 'AC (Air Conditioner)',
                'deskripsi' => 'AC Split, AC Central',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Detail untuk Aset Tetap Lainnya
            [
                'subkategori_aset_id' => $asetTetapLainnyaId,
                'kode_detail_kategori' => 'MEUBELAIR',
                'nama_detail_kategori' => 'Meubelair',
                'deskripsi' => 'Meja, Kursi, Lemari, Rak',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subkategori_aset_id' => $asetTetapLainnyaId,
                'kode_detail_kategori' => 'KENDARAAN',
                'nama_detail_kategori' => 'Kendaraan',
                'deskripsi' => 'Mobil, Motor, Truk',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('detail_kategori_asets')->insert($detailKategoris);
    }
}
