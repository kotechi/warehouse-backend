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
        Schema::create('riwayat_pemeliharaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aset_id');
            
            // Informasi Pemeliharaan
            $table->date('tanggal_pemeliharaan')->comment('Tanggal pelaksanaan pemeliharaan');
            $table->enum('jenis_pemeliharaan', [
                'preventif',
                'korektif', 
                'perbaikan',
                'service',
                'kalibrasi',
                'upgrade',
                'lainnya'
            ])->comment('Jenis pemeliharaan');
            
            $table->text('deskripsi_pemeliharaan')->comment('Deskripsi/detail pekerjaan pemeliharaan');
            $table->enum('kondisi_sebelum', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->enum('kondisi_sesudah', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            
            // Biaya
            $table->decimal('biaya', 15, 2)->default(0)->comment('Biaya pemeliharaan');
            $table->string('mata_uang', 10)->default('IDR');
            
            // Vendor/Pelaksana
            $table->string('vendor', 200)->nullable()->comment('Nama vendor/pelaksana');
            $table->string('kontak_vendor', 100)->nullable();
            $table->string('lokasi_vendor', 255)->nullable();
            
            // Dokumen
            $table->text('dokumen_pemeliharaan')->nullable()->comment('Path ke dokumen/bukti pemeliharaan');
            $table->text('foto_sebelum')->nullable();
            $table->text('foto_sesudah')->nullable();
            
            // Status & Keterangan
            $table->enum('status', ['dijadwalkan', 'sedang_dikerjakan', 'selesai', 'dibatalkan'])->default('selesai');
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            
            // Tracking
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('aset_id', 'fk_riwayat_aset')->references('id')->on('asets')->onDelete('cascade');
            $table->foreign('created_by', 'fk_riwayat_created')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by', 'fk_riwayat_updated')->references('id')->on('users')->onDelete('restrict');
            
            // Indexes
            $table->index('tanggal_pemeliharaan');
            $table->index(['aset_id', 'tanggal_pemeliharaan']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pemeliharaans');
    }
};
