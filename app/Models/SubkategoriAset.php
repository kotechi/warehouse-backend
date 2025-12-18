<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubkategoriAset extends Model
{
    use SoftDeletes;
    
    protected $table = 'subkategori_asets';
    
    protected $fillable = [
        'kategori_aset_id',
        'kode_subkategori',
        'nama_subkategori',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'kategori_aset_id' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function kategoriAset()
    {
        return $this->belongsTo(KategoriAset::class, 'kategori_aset_id');
    }

    public function detailKategoriAsets()
    {
        return $this->hasMany(DetailKategoriAset::class, 'subkategori_aset_id');
    }

    public function asets()
    {
        return $this->hasMany(Aset::class, 'subkategori_aset_id');
    }
}
