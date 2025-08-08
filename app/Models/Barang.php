<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'id',
        'produk',
        'kodegrp',
        'kategori_id',
        'status',
        'main_produk',
        'stock_awal',
        'stock_sekarang',
        'kode_qr',
        'line_divisi',
        'production_date',
        'created_by',
        'updated_by',
        'deleted_at',
    ];
}
