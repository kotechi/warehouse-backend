<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyusutanAset extends Model
{
    protected $table = 'penyusutan_asets';
    
    protected $fillable = [
        'aset_id',
        'tahun',
        'bulan',
        'nilai_perolehan',
        'nilai_residu',
        'umur_manfaat_bulan',
        'bulan_berjalan',
        'penyusutan_per_bulan',
        'akumulasi_penyusutan',
        'nilai_buku',
        'metode_penyusutan',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'aset_id' => 'integer',
        'tahun' => 'integer',
        'bulan' => 'integer',
        'nilai_perolehan' => 'decimal:2',
        'nilai_residu' => 'decimal:2',
        'umur_manfaat_bulan' => 'integer',
        'bulan_berjalan' => 'integer',
        'penyusutan_per_bulan' => 'decimal:2',
        'akumulasi_penyusutan' => 'decimal:2',
        'nilai_buku' => 'decimal:2',
    ];

    // Relationships
    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    // Method untuk menghitung penyusutan garis lurus
    public static function hitungPenyusutanGarisLurus($nilaiPerolehan, $nilaiResidu, $umurManfaatBulan)
    {
        if ($umurManfaatBulan <= 0) {
            return 0;
        }
        
        return ($nilaiPerolehan - $nilaiResidu) / $umurManfaatBulan;
    }

    // Method untuk generate penyusutan bulanan
    public static function generatePenyusutanBulanan(Aset $aset)
    {
        if (!$aset->tanggal_mulai_digunakan || !$aset->umur_manfaat_bulan) {
            return false;
        }

        $tanggalMulai = $aset->tanggal_mulai_digunakan;
        $nilaiPerolehan = $aset->nilai_perolehan;
        $nilaiResidu = $aset->nilai_residu ?? 0;
        $umurManfaatBulan = $aset->umur_manfaat_bulan;
        
        $penyusutanPerBulan = self::hitungPenyusutanGarisLurus($nilaiPerolehan, $nilaiResidu, $umurManfaatBulan);
        
        $tanggalSekarang = now();
        $bulanBerjalan = 0;
        $akumulasiPenyusutan = 0;
        
        $currentDate = \Carbon\Carbon::parse($tanggalMulai);
        
        while ($currentDate <= $tanggalSekarang && $bulanBerjalan < $umurManfaatBulan) {
            $bulanBerjalan++;
            $akumulasiPenyusutan += $penyusutanPerBulan;
            $nilaiBuku = $nilaiPerolehan - $akumulasiPenyusutan;
            
            // Cek apakah record sudah ada
            $existing = self::where('aset_id', $aset->id)
                ->where('tahun', $currentDate->year)
                ->where('bulan', $currentDate->month)
                ->first();
            
            if (!$existing) {
                self::create([
                    'aset_id' => $aset->id,
                    'tahun' => $currentDate->year,
                    'bulan' => $currentDate->month,
                    'nilai_perolehan' => $nilaiPerolehan,
                    'nilai_residu' => $nilaiResidu,
                    'umur_manfaat_bulan' => $umurManfaatBulan,
                    'bulan_berjalan' => $bulanBerjalan,
                    'penyusutan_per_bulan' => $penyusutanPerBulan,
                    'akumulasi_penyusutan' => $akumulasiPenyusutan,
                    'nilai_buku' => max(0, $nilaiBuku),
                    'metode_penyusutan' => $aset->metode_penyusutan,
                    'status' => $bulanBerjalan >= $umurManfaatBulan ? 'selesai' : 'aktif',
                ]);
            }
            
            $currentDate->addMonth();
        }
        
        return true;
    }
}
