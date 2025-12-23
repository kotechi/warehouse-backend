<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriAset extends Model
{
    use SoftDeletes;
    
    protected $table = 'kategori_asets';
    
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function subkategoriAsets()
    {
        return $this->hasMany(SubkategoriAset::class, 'kategori_aset_id');
    }

    public function asets()
    {
        return $this->hasMany(Aset::class, 'kategori_aset_id');
    }
}
