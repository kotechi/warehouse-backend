<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitEselonIi extends Model
{
    use SoftDeletes;
    
    protected $table = 'unit_eselon_iis';
    
    protected $fillable = [
        'satker_id',
        'kode_unit',
        'nama_unit',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'satker_id' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function satker()
    {
        return $this->belongsTo(Satker::class, 'satker_id');
    }

    public function penanggungJawabAsets()
    {
        return $this->hasMany(PenanggungJawabAset::class, 'unit_eselon_ii_id');
    }

    public function asets()
    {
        return $this->hasMany(Aset::class, 'unit_eselon_ii_id');
    }
}
