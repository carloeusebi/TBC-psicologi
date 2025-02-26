<?php

declare(strict_types=1);

namespace App\Enums;

enum PlanInterval: string
{
    case Monthly = 'monthly';
    case Yearly = 'yearly';

    public function label(): string
    {
        return match ($this) {
            self::Monthly => 'month',
            self::Yearly => 'year',
        };
    }
}
