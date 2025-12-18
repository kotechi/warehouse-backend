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
        Schema::create('penyusutan_asets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aset_id');
            $table->integer('tahun');
            $table->integer('bulan');
            
            // Nilai Aset
            $table->decimal('nilai_perolehan', 15, 2)->comment('Nilai perolehan aset');
            $table->decimal('nilai_residu', 15, 2)->default(0)->comment('Nilai sisa/residu');
            
            // Perhitungan Penyusutan
            $table->integer('umur_manfaat_bulan')->comment('Umur manfaat dalam bulan');
            $table->integer('bulan_berjalan')->default(0)->comment('Jumlah bulan yang sudah berjalan sejak mulai digunakan');
            $table->decimal('penyusutan_per_bulan', 15, 2)->comment('Beban penyusutan per bulan');
            
            // Akumulasi & Nilai Buku (by sistem)
            $table->decimal('akumulasi_penyusutan', 15, 2)->default(0)->comment('Total penyusutan sampai periode ini');
            $table->decimal('nilai_buku', 15, 2)->comment('Nilai perolehan - Akumulasi penyusutan');
            
            // Metode
            $table->enum('metode_penyusutan', ['garis_lurus', 'saldo_menurun'])->default('garis_lurus');
            
            // Status
            $table->enum('status', ['aktif', 'selesai', 'dihentikan'])->default('aktif');
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
            
            $table->foreign('aset_id', 'fk_penyusutan_aset')->references('id')->on('asets')->onDelete('cascade');
            
            // Unique constraint untuk mencegah duplikasi periode
            $table->unique(['aset_id', 'tahun', 'bulan']);
            
            // Indexes
            $table->index(['tahun', 'bulan']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyusutan_asets');
    }
};
