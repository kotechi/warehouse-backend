<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->string('nup', 6)->nullable()->change()->comment('Nomor Urut Pendaftaran (max 6 digit)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asets', function (Blueprint $table) {
            $table->string('nup', 100)->nullable()->change()->comment('Nomor Urut Pendaftaran (untuk aset tetap)');
        });
    }
};
