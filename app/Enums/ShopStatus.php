<?php

namespace App\Enums;

enum ShopStatus: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
