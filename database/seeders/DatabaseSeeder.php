<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Divisi;
use App\Models\Entitas;
use App\Models\Satker;
use App\Models\UnitEselonIi;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PkwuSeeder::class,
        ]);
        // Create Jabatan
        Jabatan::create([
            'jabatan' => 'Karyawan',
        ]);

        // Create Divisi
        Divisi::create([
            'kodedivisi' => 'sa-1',
            'divisi' => 'Super Admin',
            'status' => 'active',
        ]);

        // Create Entitas
        $entitas = Entitas::create([
            'kode_entitas' => 'KEMUMKM',
            'nama_entitas' => 'Kementerian UMKM RI',
            'jenis_entitas' => 'kementerian_lembaga',
            'alamat' => '-',
            'status' => 'aktif',
        ]);

        // Create Satker
        $satker = Satker::create([
            'entitas_id' => $entitas->id,
            'kode_satker' => 'DEPUTI-KEWIRAUSAHAAN',
            'nama_satker' => 'Deputi Bidang Kewirausahaan',
            'unit_eselon_i' => 'Deputi Bidang Kewirausahaan',
            'alamat' => '-',
            'status' => 'aktif',
        ]);

        // Create Unit Eselon II
        $unitEselonIIs = [
            [
                'kode_unit' => 'SEKDEP',
                'nama_unit' => 'Sekretaris Deputi Bidang Kewirausahaan',
            ],
            [
                'kode_unit' => 'ASDEP-EBW',
                'nama_unit' => 'Asdep Ekosistem Bisnis Wirausaha',
            ],
            [
                'kode_unit' => 'ASDEP-PIKU',
                'nama_unit' => 'Asdep Pendampingan Inovasi dan Keberlanjutan Usaha',
            ],
            [
                'kode_unit' => 'ASDEP-PPW',
                'nama_unit' => 'Asdep Perluasan Pembiayaan Wirausaha',
            ],
            [
                'kode_unit' => 'ASDEP-PJFPKWU',
                'nama_unit' => 'Asdep Pembinaan JF PKWU',
            ],
            [
                'kode_unit' => 'ASDEP-IDW',
                'nama_unit' => 'Asdep Inkubasi dan Digitalisasi Wirausaha',
            ],
        ];

        foreach ($unitEselonIIs as $unit) {
            UnitEselonIi::create([
                'satker_id' => $satker->id,
                'kode_unit' => $unit['kode_unit'],
                'nama_unit' => $unit['nama_unit'],
                'deskripsi' => '-',
                'status' => 'aktif',
            ]);
        }

        // Create User
        User::factory()->create([
            'name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'role' => 'superadmin',
            'jabatan_id' => 1,
            'divisi_id' => 1,
            'password' => bcrypt('superadmin123'), 
        ]);
    }
}
