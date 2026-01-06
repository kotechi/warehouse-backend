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
        Schema::table('subkategori_asets', function (Blueprint $table) {
            $table->dropUnique(['kode_subkategori']);
            $table->dropColumn('kode_subkategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subkategori_asets', function (Blueprint $table) {
            $table->string('kode_subkategori', 50)->unique()->after('kategori_aset_id');
        });
    }
};
