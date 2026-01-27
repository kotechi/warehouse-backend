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
        // 1. Drop foreign key berdasarkan NAMA ASLI
        Schema::table('penanggung_jawab_asets', function (Blueprint $table) {
            $table->dropForeign('fk_pj_aset_user');
        });

        // 2. Ubah kolom jadi nullable
        Schema::table('penanggung_jawab_asets', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // 3. Tambahkan kembali foreign key
        Schema::table('penanggung_jawab_asets', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete(); // boleh NULL saat user dihapus
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penanggung_jawab_assets', function (Blueprint $table) {
            //
        });
    }
};
