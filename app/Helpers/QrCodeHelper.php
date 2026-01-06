<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * QR Code Helper for Asset Management
 * 
 * This helper provides methods to generate QR codes for assets.
 * The QR codes can be used for asset tracking and identification.
 */
class QrCodeHelper
{
    /**
     * Generate QR code for an asset
     * 
     * @param string $kodeBarang Asset code
     * @param string $nup NUP number (optional)
     * @param int $size QR code size in pixels (default: 300)
     * @return string Base64 encoded QR code image
     */
    public static function generateForAsset(string $kodeBarang, ?string $nup = null, int $size = 300): string
    {
        // Create QR code data
        $qrData = self::createQrData($kodeBarang, $nup);
        
        // Generate QR code as base64
        $qrCode = QrCode::size($size)
            ->format('png')
            ->generate($qrData);
            
        return base64_encode($qrCode);
    }

    /**
     * Generate QR code and save to file
     * 
     * @param string $kodeBarang Asset code
     * @param string|null $nup NUP number (optional)
     * @param string $filePath File path to save the QR code
     * @param int $size QR code size in pixels (default: 300)
     * @return bool Success status
     */
    public static function generateAndSave(string $kodeBarang, ?string $nup = null, string $filePath, int $size = 300): bool
    {
        try {
            $qrData = self::createQrData($kodeBarang, $nup);
            
            QrCode::size($size)
                ->format('png')
                ->generate($qrData, $filePath);
                
            return true;
        } catch (\Exception $e) {
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create QR code data string
     * 
     * @param string $kodeBarang Asset code
     * @param string|null $nup NUP number
     * @return string QR code data
     */
    protected static function createQrData(string $kodeBarang, ?string $nup = null): string
    {
        if ($nup) {
            return "ASSET|{$kodeBarang}|{$nup}";
        }
        
        return "ASSET|{$kodeBarang}";
    }

    /**
     * Parse QR code data
     * 
     * @param string $qrData QR code data string
     * @return array ['type' => 'ASSET', 'kode_barang' => '...', 'nup' => '...']
     */
    public static function parseQrData(string $qrData): array
    {
        $parts = explode('|', $qrData);
        
        $result = [
            'type' => $parts[0] ?? null,
            'kode_barang' => $parts[1] ?? null,
            'nup' => $parts[2] ?? null,
        ];
        
        return $result;
    }

    /**
     * Generate QR code URL for asset detail page
     * 
     * @param int $assetId Asset ID
     * @param string $baseUrl Base URL of the application
     * @return string QR code data with URL
     */
    public static function generateDetailUrl(int $assetId, string $baseUrl): string
    {
        return "{$baseUrl}/aset/{$assetId}";
    }

    /**
     * Generate SVG QR code (for better scalability)
     * 
     * @param string $kodeBarang Asset code
     * @param string|null $nup NUP number
     * @param int $size QR code size
     * @return string SVG string
     */
    public static function generateSvg(string $kodeBarang, ?string $nup = null, int $size = 300): string
    {
        $qrData = self::createQrData($kodeBarang, $nup);
        
        return QrCode::size($size)
            ->format('svg')
            ->generate($qrData);
    }
}
