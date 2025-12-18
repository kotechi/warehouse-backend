<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubkategoriAsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID kategori aset tetap
        $asetTetapId = DB::table('kategori_asets')->where('kode_kategori', 'ASET_TETAP')->value('id');
        $asetLainnyaId = DB::table('kategori_asets')->where('kode_kategori', 'ASET_LAINNYA')->value('id');

        $subkategoris = [
            // Subkategori untuk Aset Tetap
            [
                'kategori_aset_id' => $asetTetapId,
                'kode_subkategori' => 'TANAH',
                'nama_subkategori' => 'Tanah',
                'deskripsi' => 'Tanah dan hak atas tanah',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetTetapId,
                'kode_subkategori' => 'PERALATAN_MESIN',
                'nama_subkategori' => 'Peralatan dan Mesin',
                'deskripsi' => 'Peralatan dan mesin untuk operasional',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetTetapId,
                'kode_subkategori' => 'GEDUNG_BANGUNAN',
                'nama_subkategori' => 'Gedung dan Bangunan',
                'deskripsi' => 'Gedung dan bangunan milik institusi',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetTetapId,
                'kode_subkategori' => 'JALAN_IRIGASI_JARINGAN',
                'nama_subkategori' => 'Jalan, Irigasi dan Jaringan',
                'deskripsi' => 'Infrastruktur jalan, irigasi dan jaringan',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetTetapId,
                'kode_subkategori' => 'ASET_TETAP_LAINNYA',
                'nama_subkategori' => 'Aset Tetap Lainnya',
                'deskripsi' => 'Aset tetap lainnya',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Subkategori untuk Aset Lainnya
            [
                'kategori_aset_id' => $asetLainnyaId,
                'kode_subkategori' => 'KEMITRAAN_PIHAK3',
                'nama_subkategori' => 'Kemitraan dengan Pihak Ketiga',
                'deskripsi' => 'Aset kemitraan dengan pihak ketiga',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetLainnyaId,
                'kode_subkategori' => 'TAK_BERWUJUD',
                'nama_subkategori' => 'Aset Tak Berwujud',
                'deskripsi' => 'Aset tak berwujud seperti software, lisensi',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetLainnyaId,
                'kode_subkategori' => 'DIHENTIKAN',
                'nama_subkategori' => 'Dihentikan dari Penggunaan',
                'deskripsi' => 'Aset yang dihentikan dari penggunaan aktif',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('subkategori_asets')->insert($subkategoris);
    }
}
