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
        Schema::create('subkategori_asets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_aset_id');
            $table->string('kode_subkategori', 50)->unique();
            $table->string('nama_subkategori', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('kategori_aset_id', 'fk_subkat_aset_kategori')->references('id')->on('kategori_asets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subkategori_asets');
    }
};
