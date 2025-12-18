<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenanggungJawabAset extends Model
{
    use SoftDeletes;
    
    protected $table = 'penanggung_jawab_asets';
    
    protected $fillable = [
        'user_id',
        'unit_eselon_ii_id',
        'nama_pic',
        'nip',
        'jabatan',
        'telepon',
        'email',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'unit_eselon_ii_id' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unitEselonIi()
    {
        return $this->belongsTo(UnitEselonIi::class, 'unit_eselon_ii_id');
    }

    public function asets()
    {
        return $this->hasMany(Aset::class, 'penanggung_jawab_aset_id');
    }
}
