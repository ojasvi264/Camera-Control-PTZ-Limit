<?php

namespace App\Enum;

enum ZoomValue: int
{
    case x1 = 1;
    case x2 = 257;
    case x3 = 513;
    case x4 = 770;
    case x5 = 1026;
    case x6 = 1282;
    case x7 = 1539;
    case x8 = 1795;
    case x9 = 2051;
    case x10 = 2308;
    case x11 = 2564;
    case x12 = 2820;

    public static function getZoomLevelFromValue(int $value): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return null;
    }
}
