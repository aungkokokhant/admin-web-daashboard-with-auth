<?php

namespace App\Enums;

enum VoucherScanStatus: string
{
    case VALID = 'valid';
    case INVALID = 'invalid';
    case EXPIRED = 'expired';
    case USED = 'used';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
