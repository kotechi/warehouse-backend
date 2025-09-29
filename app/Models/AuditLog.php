<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Barang;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'barang_id',
        'deskripsi',
        'old_values',
        'new_values',
        'input_values',
        'created_at',
        'updated_by'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    } 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
