<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;
class Barang extends Model
{
    
    protected $fillable = [
        'produk',
        'kodegrp',
        'kategori_id',
        'status',
        'main_produk',
        'stock_sekarang',
        'stock_awal',
        'kode_qr',
        'line_divisi',
        'production_date',
        'created_by',
        'updated_by',
        'divisi_id',
    ];

        

    // Cast attributes to proper types
    protected $casts = [
        'kategori_id' => 'integer',
        'main_produk' => 'integer',
        'stock_sekarang' => 'integer',
        'stock_awal' => 'integer',
    'created_by' => 'integer',
        'updated_by' => 'integer',
        'production_date' => 'date',
        'deleted_at' => 'datetime',
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
    public function stock()
    {
        return $this->hasMany(Stock::class, 'barang_id', 'id');
    }
}
