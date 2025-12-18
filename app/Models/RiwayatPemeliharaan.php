<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPemeliharaan extends Model
{
    use SoftDeletes;
    
    protected $table = 'riwayat_pemeliharaans';
    
    protected $fillable = [
        'aset_id',
        'tanggal_pemeliharaan',
        'jenis_pemeliharaan',
        'deskripsi_pemeliharaan',
        'kondisi_sebelum',
        'kondisi_sesudah',
        'biaya',
        'mata_uang',
        'vendor',
        'kontak_vendor',
        'lokasi_vendor',
        'dokumen_pemeliharaan',
        'foto_sebelum',
        'foto_sesudah',
        'status',
        'tanggal_selesai',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'aset_id' => 'integer',
        'tanggal_pemeliharaan' => 'date',
        'biaya' => 'decimal:2',
        'tanggal_selesai' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
