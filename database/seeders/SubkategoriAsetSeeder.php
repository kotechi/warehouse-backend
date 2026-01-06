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
        $asetTetapId = DB::table('kategori_asets')->where('nama_kategori', 'Aset Tetap')->value('id');
        $asetLainnyaId = DB::table('kategori_asets')->where('nama_kategori', 'Aset Lainnya')->value('id');

        $subkategoris = [
            // Subkategori untuk Aset Tetap
            [
                'kategori_aset_id' => $asetTetapId,
                'nama_subkategori' => 'Tanah',
                'deskripsi' => 'Tanah dan hak atas tanah',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetTetapId,
                'nama_subkategori' => 'Peralatan dan Mesin',
                'deskripsi' => 'Peralatan dan mesin untuk operasional',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetTetapId,
                'nama_subkategori' => 'Gedung dan Bangunan',
                'deskripsi' => 'Gedung dan bangunan milik institusi',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetTetapId,
                'nama_subkategori' => 'Jalan, Irigasi dan Jaringan',
                'deskripsi' => 'Infrastruktur jalan, irigasi dan jaringan',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetTetapId,
                'nama_subkategori' => 'Aset Tetap Lainnya',
                'deskripsi' => 'Aset tetap lainnya',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Subkategori untuk Aset Lainnya
            [
                'kategori_aset_id' => $asetLainnyaId,
                'nama_subkategori' => 'Kemitraan dengan Pihak Ketiga',
                'deskripsi' => 'Aset kemitraan dengan pihak ketiga',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetLainnyaId,
                'nama_subkategori' => 'Aset Tak Berwujud',
                'deskripsi' => 'Aset tak berwujud seperti software, lisensi',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_aset_id' => $asetLainnyaId,
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
