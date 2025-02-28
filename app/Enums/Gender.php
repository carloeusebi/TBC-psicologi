<?php

declare(strict_types=1);

namespace App\Enums;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

    /**
     * @return list<array{ key: string, label: string }>
     */
    public static function options(): array
    {
        return [
            ['key' => self::MALE->value, 'label' => self::MALE->label()],
            ['key' => self::FEMALE->value, 'label' => self::FEMALE->label()],
            ['key' => self::OTHER->value, 'label' => self::OTHER->label()],
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Maschio',
            self::FEMALE => 'Femmina',
            self::OTHER => 'Altro',
        };
    }
}
