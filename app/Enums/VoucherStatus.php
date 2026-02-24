<?php

namespace App\Enums;

enum VoucherStatus: string
{
    case UNUSED = 'unused';
    case ASSIGNED = 'assigned';
    case REDEEMED = 'redeemed';
    case EXPIRED = 'expired';
    case REVOKED = 'revoked';
    case DEACTIVATE = 'deactivate';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
