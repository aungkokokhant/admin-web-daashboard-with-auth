<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VoucherQrService
{
    public static function generate(string $payload, string $voucherCode): string
    {
        $fileName = $voucherCode . '.png';
        $path = storage_path('app/public/vouchers/' . $fileName);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        QrCode::format('png')
            ->size(600)
            ->margin(3)
            ->style('square')
            ->eye('square')
            ->backgroundColor(255, 255, 255)
            ->color(0, 0, 0)
            ->generate($payload, $path);

        // 🔥 RETURN ABSOLUTE FILESYSTEM PATH
        return $path;
    }
}
