<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use SoftDeletes;
    
    protected $table = 'asets';
    
    protected $fillable = [
        'kode_barang',
        'nup',
        'kategori_aset_id',
        'subkategori_aset_id',
        'detail_kategori_aset_id',
        'nama_aset',
        'spesifikasi',
        'jumlah',
        'satuan',
        'tanggal_perolehan',
        'nilai_perolehan',
        'mata_uang',
        'sumber_perolehan',
        'entitas_id',
        'satker_id',
        'unit_eselon_ii_id',
        'penanggung_jawab_aset_id',
        'unit_pemakai',
        'kondisi_fisik',
        'tanggal_mulai_digunakan',
        'status',
        'umur_manfaat_bulan',
        'metode_penyusutan',
        'nilai_residu',
        'akumulasi_penyusutan',
        'lokasi_fisik',
        'ruangan',
        'kode_qr',
        'tag_rfid',
        'foto_aset',
        'dokumen_perolehan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'kategori_aset_id' => 'integer',
        'subkategori_aset_id' => 'integer',
        'detail_kategori_aset_id' => 'integer',
        'jumlah' => 'integer',
        'tanggal_perolehan' => 'date',
        'nilai_perolehan' => 'decimal:2',
        'entitas_id' => 'integer',
        'satker_id' => 'integer',
        'unit_eselon_ii_id' => 'integer',
        'penanggung_jawab_aset_id' => 'integer',
        'tanggal_mulai_digunakan' => 'date',
        'umur_manfaat_bulan' => 'integer',
        'nilai_residu' => 'decimal:2',
        'akumulasi_penyusutan' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function kategoriAset()
    {
        return $this->belongsTo(KategoriAset::class, 'kategori_aset_id');
    }

    public function subkategoriAset()
    {
        return $this->belongsTo(SubkategoriAset::class, 'subkategori_aset_id');
    }

    public function detailKategoriAset()
    {
        return $this->belongsTo(DetailKategoriAset::class, 'detail_kategori_aset_id');
    }

    public function entitas()
    {
        return $this->belongsTo(Entitas::class, 'entitas_id');
    }

    public function satker()
    {
        return $this->belongsTo(Satker::class, 'satker_id');
    }

    public function unitEselonIi()
    {
        return $this->belongsTo(UnitEselonIi::class, 'unit_eselon_ii_id');
    }

    public function penanggungJawabAset()
    {
        return $this->belongsTo(PenanggungJawabAset::class, 'penanggung_jawab_aset_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function penyusutanAsets()
    {
        return $this->hasMany(PenyusutanAset::class, 'aset_id');
    }

    public function riwayatPemeliharaans()
    {
        return $this->hasMany(RiwayatPemeliharaan::class, 'aset_id');
    }

    public function penghapusanPemindahtangananAsets()
    {
        return $this->hasMany(PenghapusanPemindahtangananAset::class, 'aset_id');
    }

    // Accessor untuk nilai buku terkini
    public function getNilaiBukuAttribute()
    {
        $latestPenyusutan = $this->penyusutanAsets()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();
            
        if ($latestPenyusutan) {
            return $latestPenyusutan->nilai_buku;
        }
        
        return $this->nilai_perolehan;
    }

    // Accessor untuk akumulasi penyusutan terkini
    public function getAkumulasiPenyusutanAttribute()
    {
        $latestPenyusutan = $this->penyusutanAsets()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();
            
        if ($latestPenyusutan) {
            return $latestPenyusutan->akumulasi_penyusutan;
        }
        
        return 0;
    }

    // Check if asset is near expiration (48 hours)
    public function isNearExpiration()
    {
        if (!$this->tanggal_mulai_digunakan || !$this->umur_manfaat_bulan) {
            return false;
        }

        $expirationDate = $this->tanggal_mulai_digunakan->copy()->addMonths($this->umur_manfaat_bulan);
        $hoursUntilExpiration = now()->diffInHours($expirationDate, false);

        return $hoursUntilExpiration > 0 && $hoursUntilExpiration <= 48;
    }

    // Get expiration date
    public function getExpirationDateAttribute()
    {
        if (!$this->tanggal_mulai_digunakan || !$this->umur_manfaat_bulan) {
            return null;
        }

        return $this->tanggal_mulai_digunakan->copy()->addMonths($this->umur_manfaat_bulan);
    }

    // Scope for assets near expiration
    public function scopeNearExpiration($query)
    {
        return $query->whereNotNull('tanggal_mulai_digunakan')
            ->whereNotNull('umur_manfaat_bulan')
            ->whereRaw('DATE_ADD(tanggal_mulai_digunakan, INTERVAL umur_manfaat_bulan MONTH) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 48 HOUR)');
    }
}
