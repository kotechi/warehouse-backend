<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entitas extends Model
{
    use SoftDeletes;
    
    protected $table = 'entitas';
    
    protected $fillable = [
        'kode_entitas',
        'nama_entitas',
        'jenis_entitas',
        'alamat',
        'status',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function satkers()
    {
        return $this->hasMany(Satker::class, 'entitas_id');
    }

    public function asets()
    {
        return $this->hasMany(Aset::class, 'entitas_id');
    }
}
