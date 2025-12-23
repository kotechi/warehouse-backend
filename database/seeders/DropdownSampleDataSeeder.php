<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DropdownSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Usage: php artisan db:seed --class=DropdownSampleDataSeeder
     */
    public function run()
    {
        $now = Carbon::now();

        // 1. Kategori Aset
        DB::table('kategori_asets')->insert([
            [
                'kode_kategori' => 'AT',
                'nama_kategori' => 'Aset Tetap',
                'deskripsi' => 'Aset yang memiliki umur manfaat lebih dari satu tahun',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_kategori' => 'ABT',
                'nama_kategori' => 'Aset Bergerak Tetap',
                'deskripsi' => 'Aset bergerak yang digunakan dalam operasional',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_kategori' => 'ATB',
                'nama_kategori' => 'Aset Tidak Berwujud',
                'deskripsi' => 'Aset yang tidak memiliki bentuk fisik',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 2. Subkategori Aset
        $kategoriAsetTetap = DB::table('kategori_asets')->where('kode_kategori', 'AT')->first();
        
        DB::table('subkategori_asets')->insert([
            [
                'kategori_aset_id' => $kategoriAsetTetap->id,
                'kode_subkategori' => 'PK',
                'nama_subkategori' => 'Peralatan Kantor',
                'deskripsi' => 'Peralatan yang digunakan untuk operasional kantor',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kategori_aset_id' => $kategoriAsetTetap->id,
                'kode_subkategori' => 'PC',
                'nama_subkategori' => 'Peralatan Komputer',
                'deskripsi' => 'Peralatan komputer dan teknologi informasi',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kategori_aset_id' => $kategoriAsetTetap->id,
                'kode_subkategori' => 'FURN',
                'nama_subkategori' => 'Furniture',
                'deskripsi' => 'Mebel dan perabotan kantor',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 3. Detail Kategori Aset
        $subkategoriKomputer = DB::table('subkategori_asets')->where('kode_subkategori', 'PC')->first();
        
        DB::table('detail_kategori_asets')->insert([
            [
                'subkategori_aset_id' => $subkategoriKomputer->id,
                'kode_detail_kategori' => 'LT',
                'nama_detail_kategori' => 'Laptop',
                'deskripsi' => 'Komputer portable/laptop',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'subkategori_aset_id' => $subkategoriKomputer->id,
                'kode_detail_kategori' => 'DT',
                'nama_detail_kategori' => 'Desktop',
                'deskripsi' => 'Komputer desktop/PC',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'subkategori_aset_id' => $subkategoriKomputer->id,
                'kode_detail_kategori' => 'PRN',
                'nama_detail_kategori' => 'Printer',
                'deskripsi' => 'Perangkat printer',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 4. Entitas
        DB::table('entitas')->insert([
            [
                'kode_entitas' => 'KEMENKEU',
                'nama_entitas' => 'Kementerian Keuangan',
                'jenis_entitas' => 'Kementerian',
                'alamat' => 'Jl. Lapangan Banteng Timur No.2-4, Jakarta Pusat',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kode_entitas' => 'KEMENDAGRI',
                'nama_entitas' => 'Kementerian Dalam Negeri',
                'jenis_entitas' => 'Kementerian',
                'alamat' => 'Jl. Medan Merdeka Utara No.7, Jakarta Pusat',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 5. Satker
        $entitasKemenkeu = DB::table('entitas')->where('kode_entitas', 'KEMENKEU')->first();
        
        DB::table('satkers')->insert([
            [
                'entitas_id' => $entitasKemenkeu->id,
                'kode_satker' => 'KW-DKI',
                'nama_satker' => 'Kantor Wilayah DKI Jakarta',
                'unit_eselon_i' => 'Direktorat Jenderal Kekayaan Negara',
                'alamat' => 'Jl. Budi Kemuliaan No.1, Jakarta Pusat',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'entitas_id' => $entitasKemenkeu->id,
                'kode_satker' => 'KW-JABAR',
                'nama_satker' => 'Kantor Wilayah Jawa Barat',
                'unit_eselon_i' => 'Direktorat Jenderal Kekayaan Negara',
                'alamat' => 'Jl. Asia Afrika No.114, Bandung',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 6. Unit Eselon II
        $satkerDKI = DB::table('satkers')->where('kode_satker', 'KW-DKI')->first();
        
        DB::table('unit_eselon_iis')->insert([
            [
                'satker_id' => $satkerDKI->id,
                'kode_unit' => 'BK',
                'nama_unit' => 'Bagian Keuangan',
                'deskripsi' => 'Unit yang mengelola keuangan',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'satker_id' => $satkerDKI->id,
                'kode_unit' => 'BU',
                'nama_unit' => 'Bagian Umum',
                'deskripsi' => 'Unit yang mengelola administrasi umum',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 7. Penanggung Jawab Aset
        $unitKeuangan = DB::table('unit_eselon_iis')->where('kode_unit', 'BK')->first();
        
        DB::table('penanggung_jawab_asets')->insert([
            [
                'user_id' => null, // Set jika ada relasi ke user
                'unit_eselon_ii_id' => $unitKeuangan->id,
                'nama_pic' => 'Budi Santoso',
                'nip' => '198501012010011001',
                'jabatan' => 'Kepala Bagian Keuangan',
                'telepon' => '081234567890',
                'email' => 'budi.santoso@kemenkeu.go.id',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => null,
                'unit_eselon_ii_id' => $unitKeuangan->id,
                'nama_pic' => 'Siti Rahayu',
                'nip' => '198705052011012002',
                'jabatan' => 'Staf Pengelola BMN',
                'telepon' => '081234567891',
                'email' => 'siti.rahayu@kemenkeu.go.id',
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->command->info('✅ Sample data untuk dropdown berhasil dibuat!');
        $this->command->info('📊 Data yang dibuat:');
        $this->command->info('   - Kategori Aset: 3 records');
        $this->command->info('   - Subkategori Aset: 3 records');
        $this->command->info('   - Detail Kategori Aset: 3 records');
        $this->command->info('   - Entitas: 2 records');
        $this->command->info('   - Satker: 2 records');
        $this->command->info('   - Unit Eselon II: 2 records');
        $this->command->info('   - Penanggung Jawab Aset: 2 records');
    }
}
