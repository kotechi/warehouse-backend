<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitEselonIi;
use App\Models\PenanggungJawabAset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PkwuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Import from CSV
        $csvFile = database_path('seeders/pkwu.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan: {$csvFile}");
            return;
        }

        $file = fopen($csvFile, 'r');

        // Skip header row
        fgetcsv($file, 0, ';');

        $rowNumber = 1;
        while (($row = fgetcsv($file, 0, ';')) !== false) {
            // Skip empty rows
            if (empty($row[0]) || trim($row[0]) === '') {
                continue;
            }

            $nip = trim($row[1] ?? '');
            $nama = trim($row[2] ?? '');
            $jenisKelamin = trim($row[3] ?? '');
            $agama = trim($row[4] ?? '');
            $jabatan = trim($row[5] ?? '');
            $unitKerja = trim($row[6] ?? '');
            $telepon = trim($row[7] ?? '');

            // Generate email dari nama
            $emailUsername = strtolower(str_replace([' ', '.', ','], ['', '', ''], explode(',', $nama)[0]));
            $email = $emailUsername . '@kemenkumkm.go.id';

            // Create User
            $user = User::create([
                'name' => $nama,
                'email' => $email,
                'role' => 'user',
                'jabatan_id' => 1,
                'divisi_id' => 1,
                'password' => Hash::make('password123'),
            ]);

            // Find matching Unit Eselon II
            $unitEselonIi = null;

            if (str_contains($unitKerja, 'Sekretariat Deputi')) {
                $unitEselonIi = UnitEselonIi::where('kode_unit', 'SEKDEP')->first();
            } elseif (str_contains($unitKerja, 'Ekosistem Bisnis')) {
                $unitEselonIi = UnitEselonIi::where('kode_unit', 'ASDEP-EBW')->first();
            } elseif (str_contains($unitKerja, 'Pendampingan Inovasi')) {
                $unitEselonIi = UnitEselonIi::where('kode_unit', 'ASDEP-PIKU')->first();
            } elseif (str_contains($unitKerja, 'Perluasan Pembiayaan')) {
                $unitEselonIi = UnitEselonIi::where('kode_unit', 'ASDEP-PPW')->first();
            } elseif (str_contains($unitKerja, 'Pembinaan JF')) {
                $unitEselonIi = UnitEselonIi::where('kode_unit', 'ASDEP-PJFPKWU')->first();
            } elseif (str_contains($unitKerja, 'Inkubasi dan Digitalisasi')) {
                $unitEselonIi = UnitEselonIi::where('kode_unit', 'ASDEP-IDW')->first();
            }

            // Default to SEKDEP if no match found
            if (!$unitEselonIi) {
                $unitEselonIi = UnitEselonIi::where('kode_unit', 'SEKDEP')->first();
            }

            // Create Penanggung Jawab Aset
            PenanggungJawabAset::create([
                'user_id' => $user->id,
                'unit_eselon_ii_id' => $unitEselonIi->id,
                'nama_pic' => $nama,
                'nip' => $nip,
                'jabatan' => $jabatan,
                'telepon' => $telepon,
                'email' => $email,
                'status' => 'aktif',
            ]);

            $this->command->info("Imported: {$nama} ({$email})");
            $rowNumber++;
        }

        fclose($file);

        $this->command->info("Total rows imported: " . ($rowNumber - 1));
    }
}
