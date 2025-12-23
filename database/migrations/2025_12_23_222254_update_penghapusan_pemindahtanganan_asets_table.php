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
        Schema::table('penghapusan_pemindahtanganan_asets', function (Blueprint $table) {
            // Rename tanggal_pengajuan to tanggal
            $table->renameColumn('tanggal_pengajuan', 'tanggal');
            
            // Add dasar_persetujuan (replacing nomor_surat_persetujuan)
            $table->text('dasar_persetujuan')->nullable()->after('alasan')->comment('Dasar persetujuan penghapusan/pemindahtanganan');
            
            // Rename tanggal_keluar_daftar to tanggal_pemindahan
            $table->renameColumn('tanggal_keluar_daftar', 'tanggal_pemindahan');
            
            // Rename dokumen_bukti to upload_bukti
            $table->renameColumn('dokumen_bukti', 'upload_bukti');
            
            // Remove informasi penerima fields for pindah tangan
            $table->dropColumn(['pihak_penerima', 'alamat_penerima', 'kontak_penerima']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penghapusan_pemindahtanganan_asets', function (Blueprint $table) {
            $table->renameColumn('tanggal', 'tanggal_pengajuan');
            $table->dropColumn('dasar_persetujuan');
            $table->renameColumn('tanggal_pemindahan', 'tanggal_keluar_daftar');
            $table->renameColumn('upload_bukti', 'dokumen_bukti');
            
            // Restore penerima fields
            $table->string('pihak_penerima', 200)->nullable();
            $table->text('alamat_penerima')->nullable();
            $table->string('kontak_penerima', 100)->nullable();
        });
    }
};
