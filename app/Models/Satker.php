<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satker extends Model
{
    use SoftDeletes;
    
    protected $table = 'satkers';
    
    protected $fillable = [
        'entitas_id',
        'kode_satker',
        'nama_satker',
        'unit_eselon_i',
        'alamat',
        'status',
    ];

    protected $casts = [
        'entitas_id' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function entitas()
    {
        return $this->belongsTo(Entitas::class, 'entitas_id');
    }

    public function unitEselonIis()
    {
        return $this->hasMany(UnitEselonIi::class, 'satker_id');
    }

    public function asets()
    {
        return $this->hasMany(Aset::class, 'satker_id');
    }
}
