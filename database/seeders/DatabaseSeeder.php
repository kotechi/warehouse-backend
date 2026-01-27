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
    public function run(): void
    {
        // 1. MASTER DATA
        Jabatan::create([
            'jabatan' => 'Karyawan',
        ]);

        Divisi::create([
            'kodedivisi' => 'sa-1',
            'divisi' => 'Super Admin',
            'status' => 'active',
        ]);

        $entitas = Entitas::create([
            'kode_entitas' => 'KEMUMKM',
            'nama_entitas' => 'Kementerian UMKM RI',
            'jenis_entitas' => 'kementerian_lembaga',
            'alamat' => '-',
            'status' => 'aktif',
        ]);

        $satker = Satker::create([
            'entitas_id' => $entitas->id,
            'kode_satker' => 'DEPUTI-KEWIRAUSAHAAN',
            'nama_satker' => 'Deputi Bidang Kewirausahaan',
            'unit_eselon_i' => 'Deputi Bidang Kewirausahaan',
            'alamat' => '-',
            'status' => 'aktif',
        ]);

        // 2. UNIT ESELON II
        $unitEselonIIs = [
            ['kode_unit' => 'SEKDEP', 'nama_unit' => 'Sekretaris Deputi Bidang Kewirausahaan'],
            ['kode_unit' => 'ASDEP-EBW', 'nama_unit' => 'Asdep Ekosistem Bisnis Wirausaha'],
            ['kode_unit' => 'ASDEP-PIKU', 'nama_unit' => 'Asdep Pendampingan Inovasi dan Keberlanjutan Usaha'],
            ['kode_unit' => 'ASDEP-PPW', 'nama_unit' => 'Asdep Perluasan Pembiayaan Wirausaha'],
            ['kode_unit' => 'ASDEP-PJFPKWU', 'nama_unit' => 'Asdep Pembinaan JF PKWU'],
            ['kode_unit' => 'ASDEP-IDW', 'nama_unit' => 'Asdep Inkubasi dan Digitalisasi Wirausaha'],
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

        // 3. USER (WAJIB SEBELUM PKWU)
        $user = User::factory()->create([
            'name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'role' => 'superadmin',
            'jabatan_id' => 1,
            'divisi_id' => 1,
            'password' => bcrypt('superadmin123'),
        ]);

        // 4. BARU JALANKAN PKWU
        $this->call([
            PkwuSeeder::class,
        ]);
    }
}
