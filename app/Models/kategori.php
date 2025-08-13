<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    protected $table = 'kategoris';
    
    protected $fillable = [
        'id',
        'kategori',
        'status',
        'deleted_at'
    ];
}
