<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class divisi extends Model
{
    protected $table = 'divisis';
    protected $fillable = [
        'kodedivisi',
        'divisi',
        'status',
        'deleted_at'
    ];
}
