<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Divisi;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'role' => 'superadmin',
            'jabatan_id' => 1,
            'divisi_id' => 1,
            'password' => bcrypt('superadmin123'), 
        ]);
        Jabatan::create([
            'jabatan' => 'admin',
        ]);

        Divisi::create([
            'kodedivisi' => 'sa-1',
            'divisi' => 'Super Admin',
            'status' => 'active',
        ]);

    }
}
