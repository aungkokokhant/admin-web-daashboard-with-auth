<?php

namespace App\Support;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class VoucherCrypto
{
    public static function encrypt(int $voucherId, string $voucherCode): string
    {
        $payload = json_encode([
            'vid' => $voucherId,
            'code' => $voucherCode,
        ]);

        return Crypt::encryptString($payload);
    }

    public static function decrypt(string $encrypted): array
    {
        try {
            $json = Crypt::decryptString($encrypted);
            return json_decode($json, true);
        } catch (DecryptException $e) {
            throw new \RuntimeException('Invalid voucher QR payload');
        }
    }
}
