<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenghapusanPemindahtangananAset extends Model
{
    use SoftDeletes;
    
    protected $table = 'penghapusan_pemindahtanganan_asets';
    
    protected $fillable = [
        'aset_id',
        'jenis_tindakan',
        'tanggal_pengajuan',
        'alasan',
        'nilai_buku_saat_ini',
        'nilai_transaksi',
        'kondisi_aset',
        'pihak_penerima',
        'alamat_penerima',
        'kontak_penerima',
        'entitas_tujuan_id',
        'satker_tujuan_id',
        'unit_eselon_ii_tujuan_id',
        'penanggung_jawab_aset_tujuan_id',
        'nomor_surat_persetujuan',
        'tanggal_persetujuan',
        'pejabat_menyetujui',
        'dokumen_persetujuan',
        'tanggal_keluar_daftar',
        'dokumen_bukti',
        'status',
        'catatan',
        'alasan_penolakan',
        'created_by',
        'updated_by',
        'approved_by',
    ];

    protected $casts = [
        'aset_id' => 'integer',
        'tanggal_pengajuan' => 'date',
        'nilai_buku_saat_ini' => 'decimal:2',
        'nilai_transaksi' => 'decimal:2',
        'entitas_tujuan_id' => 'integer',
        'satker_tujuan_id' => 'integer',
        'unit_eselon_ii_tujuan_id' => 'integer',
        'penanggung_jawab_aset_tujuan_id' => 'integer',
        'tanggal_persetujuan' => 'date',
        'tanggal_keluar_daftar' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'approved_by' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function entitasTujuan()
    {
        return $this->belongsTo(Entitas::class, 'entitas_tujuan_id');
    }

    public function satkerTujuan()
    {
        return $this->belongsTo(Satker::class, 'satker_tujuan_id');
    }

    public function unitEselonIiTujuan()
    {
        return $this->belongsTo(UnitEselonIi::class, 'unit_eselon_ii_tujuan_id');
    }

    public function penanggungJawabAsetTujuan()
    {
        return $this->belongsTo(PenanggungJawabAset::class, 'penanggung_jawab_aset_tujuan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
