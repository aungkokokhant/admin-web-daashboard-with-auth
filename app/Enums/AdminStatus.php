<?php

namespace App\Enums;

enum AdminStatus: string
{
    case ACTIVE = 'active';
    case DISABLED = 'disabled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
