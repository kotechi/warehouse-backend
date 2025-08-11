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

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'line_divisi');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
