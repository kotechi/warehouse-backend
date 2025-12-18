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
        Schema::create('detail_kategori_asets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subkategori_aset_id');
            $table->string('kode_detail_kategori', 50)->unique();
            $table->string('nama_detail_kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('subkategori_aset_id', 'fk_detail_kat_subkat')->references('id')->on('subkategori_asets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kategori_asets');
    }
};
