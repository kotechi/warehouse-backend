<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Barang;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $fillable = [
        'barang_id',
        'user_id',
        'kode_qr',
        'stock',
        'keterangan',
        'production_date',
        'type'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
