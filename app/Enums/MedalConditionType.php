<?php

namespace App\Enums;

enum MedalConditionType: string
{
    case AvoidedAmount = 'avoided_amount';
    case AvoidedCount = 'avoided_count';
    case ItemCount = 'item_count';

    public function format(int $value): string
    {
        return match ($this) {
            self::AvoidedAmount => format_jpy($value),
            self::AvoidedCount, self::ItemCount => "{$value}件"
        };
    }
}
