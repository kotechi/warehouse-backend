<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriAsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'kode_kategori' => 'PERSEDIAAN',
                'nama_kategori' => 'Persediaan',
                'deskripsi' => 'Barang persediaan untuk operasional',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'ASET_TETAP',
                'nama_kategori' => 'Aset Tetap',
                'deskripsi' => 'Aset tetap yang digunakan dalam operasional',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'ASET_LANCAR',
                'nama_kategori' => 'Aset Lancar',
                'deskripsi' => 'Aset lancar perusahaan',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'KDP',
                'nama_kategori' => 'Konstruksi Dalam Pengerjaan',
                'deskripsi' => 'Aset yang masih dalam tahap pembangunan',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'ASET_LAINNYA',
                'nama_kategori' => 'Aset Lainnya',
                'deskripsi' => 'Aset lainnya yang tidak termasuk kategori utama',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('kategori_asets')->insert($kategoris);
    }
}
