<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailKategoriAset extends Model
{
    use SoftDeletes;
    
    protected $table = 'detail_kategori_asets';
    
    protected $fillable = [
        'subkategori_aset_id',
        'kode_detail_kategori',
        'nama_detail_kategori',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'subkategori_aset_id' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function subkategoriAset()
    {
        return $this->belongsTo(SubkategoriAset::class, 'subkategori_aset_id');
    }

    public function asets()
    {
        return $this->hasMany(Aset::class, 'detail_kategori_aset_id');
    }
}
