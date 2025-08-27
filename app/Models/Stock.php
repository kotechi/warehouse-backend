<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $fillable = [
        'barang_id',
        'user_id',
        'stock',
        'keterangan',
        'production_date',
        'type'
    ];
}
