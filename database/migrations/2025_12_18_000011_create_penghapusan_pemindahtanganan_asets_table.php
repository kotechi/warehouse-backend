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
        Schema::create('penghapusan_pemindahtanganan_asets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aset_id');
            
            // Rencana Tindakan
            $table->enum('jenis_tindakan', [
                'jual',
                'hibah',
                'pindah_tangan',
                'tukar_menukar',
                'penyertaan_modal',
                'pemusnahan',
                'penghapusan'
            ])->comment('Jenis tindakan');
            
            // Informasi Tindakan
            $table->date('tanggal_pengajuan')->comment('Tanggal pengajuan');
            $table->text('alasan')->comment('Alasan penghapusan/pemindahtanganan');
            
            // Nilai & Kondisi
            $table->decimal('nilai_buku_saat_ini', 15, 2)->comment('Nilai buku pada saat penghapusan');
            $table->decimal('nilai_transaksi', 15, 2)->nullable()->comment('Nilai jual/tukar (jika ada)');
            $table->enum('kondisi_aset', ['baik', 'rusak_ringan', 'rusak_berat'])->comment('Kondisi aset saat dihapus');
            
            // Penerima/Pihak Terkait (untuk pindah tangan, hibah, jual)
            $table->string('pihak_penerima', 200)->nullable()->comment('Nama pihak penerima (untuk hibah, jual, pindah tangan)');
            $table->text('alamat_penerima')->nullable();
            $table->string('kontak_penerima', 100)->nullable();
            
            // Untuk Pindah Tangan Internal
            $table->unsignedBigInteger('entitas_tujuan_id')->nullable();
            $table->unsignedBigInteger('satker_tujuan_id')->nullable();
            $table->unsignedBigInteger('unit_eselon_ii_tujuan_id')->nullable();
            $table->unsignedBigInteger('penanggung_jawab_aset_tujuan_id')->nullable();
            
            // Persetujuan & Dokumen
            $table->string('nomor_surat_persetujuan', 100)->nullable()->comment('Nomor SK/Surat persetujuan');
            $table->date('tanggal_persetujuan')->nullable()->comment('Tanggal persetujuan');
            $table->string('pejabat_menyetujui', 150)->nullable();
            $table->text('dokumen_persetujuan')->nullable()->comment('Path ke dokumen persetujuan');
            
            // Tanggal Dikeluarkan dari Daftar
            $table->date('tanggal_keluar_daftar')->nullable()->comment('Tanggal dikeluarkan dari DBKP/KIB');
            $table->text('dokumen_bukti')->nullable()->comment('Path ke dokumen bukti (BAST, berita acara pemusnahan, dll)');
            
            // Status & Keterangan
            $table->enum('status', [
                'draft',
                'diajukan',
                'disetujui',
                'ditolak',
                'dalam_proses',
                'selesai',
                'dibatalkan'
            ])->default('draft');
            
            $table->text('catatan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            
            // Tracking
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('aset_id', 'fk_hapus_aset')->references('id')->on('asets')->onDelete('cascade');
            $table->foreign('entitas_tujuan_id', 'fk_hapus_entitas')->references('id')->on('entitas')->onDelete('set null');
            $table->foreign('satker_tujuan_id', 'fk_hapus_satker')->references('id')->on('satkers')->onDelete('set null');
            $table->foreign('unit_eselon_ii_tujuan_id', 'fk_hapus_unit_es2')->references('id')->on('unit_eselon_iis')->onDelete('set null');
            $table->foreign('penanggung_jawab_aset_tujuan_id', 'fk_hapus_pj')->references('id')->on('penanggung_jawab_asets')->onDelete('set null');
            $table->foreign('created_by', 'fk_hapus_created')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by', 'fk_hapus_updated')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('approved_by', 'fk_hapus_approved')->references('id')->on('users')->onDelete('restrict');
            
            // Indexes
            $table->index('jenis_tindakan');
            $table->index('status');
            $table->index('tanggal_pengajuan');
            $table->index(['aset_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penghapusan_pemindahtanganan_asets');
    }
};
