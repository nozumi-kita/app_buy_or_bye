<?php

namespace App\Enums;

enum ItemStatus: string
{
    case Pending = 'pending';
    case Purchased = 'purchased';
    case PurchaseAvoided = 'purchase_avoided';

    public function label(): string
    {
        return match ($this) {
            self::Pending => '保留中',
            self::Purchased => '購入済',
            self::PurchaseAvoided => '見送り',
        };
    }
}
