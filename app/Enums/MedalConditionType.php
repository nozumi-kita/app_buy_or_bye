<?php

namespace App\Enums;

enum MedalConditionType: string
{
    case AvoidedAmount = 'avoided_amount';
    case AvoidedCount = 'avoided_count';
    case ItemCount = 'item_count';
}
