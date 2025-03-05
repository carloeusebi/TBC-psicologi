<?php

declare(strict_types=1);

namespace App\Enums;

enum Plan: string
{
    case BASIC = 'Basic';
    case PRO = 'Pro';
    case UNLIMITED = 'Unlimited';
}
