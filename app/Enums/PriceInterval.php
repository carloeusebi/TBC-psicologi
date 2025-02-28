<?php

declare(strict_types=1);

namespace App\Enums;

enum PriceInterval: string
{
    case Monthly = 'month';
    case Yearly = 'year';

    public function label(): string
    {
        return match ($this) {
            self::Monthly => 'month',
            self::Yearly => 'year',
        };
    }

    public function translatedLabel(): string
    {
        return match ($this) {
            self::Monthly => 'mese',
            self::Yearly => 'anno',
        };
    }
}
