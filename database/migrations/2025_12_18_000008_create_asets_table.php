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
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            
            // Data Inti Aset (wajib)
            $table->string('kode_barang', 100)->unique()->comment('Kode Barang sesuai standar BMN');
            $table->string('nup', 100)->nullable()->comment('Nomor Urut Pendaftaran (untuk aset tetap)');
            
            // Kategori/Klasifikasi
            $table->unsignedBigInteger('kategori_aset_id')->comment('Persediaan/Aset Tetap/Aset Lancar/KDP/Aset Lainnya');
            $table->unsignedBigInteger('subkategori_aset_id')->nullable()->comment('Subkategori aset');
            $table->unsignedBigInteger('detail_kategori_aset_id')->nullable()->comment('Detail kategori: PC/Laptop, Printer, Camera, Meubelair, dll');
            
            // Informasi Barang
            $table->string('nama_aset', 255)->comment('Nama Barang/Aset');
            $table->text('spesifikasi')->nullable()->comment('Merk/Tipe/Ukuran/Kapasitas/Serial Number/IMEI/No Rangka-Mesin');
            
            // Jumlah & Satuan
            $table->integer('jumlah')->default(1)->comment('Jumlah unit');
            $table->string('satuan', 50)->default('unit')->comment('Satuan (unit, buah, set, dll)');
            
            // Perolehan
            $table->date('tanggal_perolehan')->nullable()->comment('Tanggal Perolehan / Tanggal BAST');
            $table->decimal('nilai_perolehan', 15, 2)->default(0)->comment('Nilai Perolehan dalam IDR');
            $table->string('mata_uang', 10)->default('IDR');
            $table->enum('sumber_perolehan', [
                'pembelian', 
                'hibah', 
                'tukar_menukar', 
                'penyertaan_modal', 
                'hasil_pembangunan',
                'lainnya'
            ])->default('pembelian')->comment('Sumber Perolehan');
            $table->text('keterangan_sumber_perolehan')->nullable();
            
            // Kepemilikan & Struktur Organisasi
            $table->unsignedBigInteger('entitas_id')->nullable();
            $table->unsignedBigInteger('satker_id')->nullable();
            $table->unsignedBigInteger('unit_eselon_ii_id')->nullable();
            $table->unsignedBigInteger('penanggung_jawab_aset_id')->nullable()->comment('PIC');
            $table->string('unit_pemakai', 150)->nullable();
            
            // Kondisi & Status
            $table->enum('kondisi_fisik', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->date('tanggal_mulai_digunakan')->nullable();
            $table->enum('status', ['aktif', 'dalam_pemeliharaan', 'rusak', 'dipindahtangankan', 'dihapus'])->default('aktif');
            
            // Umur Manfaat & Penyusutan (untuk aset tetap)
            $table->integer('umur_manfaat_bulan')->nullable()->comment('Umur manfaat dalam bulan');
            $table->enum('metode_penyusutan', ['garis_lurus', 'saldo_menurun', 'tidak_disusutkan'])->default('garis_lurus');
            $table->decimal('nilai_residu', 15, 2)->default(0)->comment('Nilai sisa/residu');
            
            // Lokasi
            $table->string('lokasi_fisik', 255)->nullable()->comment('Lokasi fisik aset');
            $table->string('ruangan', 100)->nullable();
            
            // QR Code & Identitas
            $table->string('kode_qr', 255)->nullable()->unique();
            $table->string('tag_rfid', 100)->nullable();
            
            // Dokumentasi
            $table->text('foto_aset')->nullable()->comment('Path ke file foto');
            $table->text('dokumen_perolehan')->nullable()->comment('Path ke dokumen BAST/bukti perolehan');
            
            // Audit & Tracking
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('kategori_aset_id', 'fk_aset_kategori')->references('id')->on('kategori_asets')->onDelete('restrict');
            $table->foreign('subkategori_aset_id', 'fk_aset_subkat')->references('id')->on('subkategori_asets')->onDelete('restrict');
            $table->foreign('detail_kategori_aset_id', 'fk_aset_detail_kat')->references('id')->on('detail_kategori_asets')->onDelete('restrict');
            $table->foreign('entitas_id', 'fk_aset_entitas')->references('id')->on('entitas')->onDelete('set null');
            $table->foreign('satker_id', 'fk_aset_satker')->references('id')->on('satkers')->onDelete('set null');
            $table->foreign('unit_eselon_ii_id', 'fk_aset_unit_es2')->references('id')->on('unit_eselon_iis')->onDelete('set null');
            $table->foreign('penanggung_jawab_aset_id', 'fk_aset_pj')->references('id')->on('penanggung_jawab_asets')->onDelete('set null');
            $table->foreign('created_by', 'fk_aset_created')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by', 'fk_aset_updated')->references('id')->on('users')->onDelete('restrict');
            
            // Indexes
            $table->index(['status', 'kondisi_fisik']);
            $table->index('tanggal_perolehan');
            $table->index('nilai_perolehan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
