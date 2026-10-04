<?php

namespace App\Support;

use App\Enums\ItemStatus;
use App\Enums\MedalConditionType;
use App\Models\Medal;
use App\Models\User;

final class MedalProgress
{
    private function __construct(private readonly array $values) {}

    public static function for(User $user): self
    {
        $row = $user->items()
            ->toBase()
            ->selectRaw('COUNT(*) FILTER (WHERE status = ?) AS avoided_count', [ItemStatus::PurchaseAvoided->value])
            ->selectRaw('COALESCE(SUM(price) FILTER ( WHERE status = ?), 0) AS avoided_amount', [ItemStatus::PurchaseAvoided->value])
            ->selectRaw('COUNT(*) AS item_count')
            ->first();

        return new self([
            MedalConditionType::AvoidedCount->value => (int) $row->avoided_count,
            MedalConditionType::AvoidedAmount->value => (int) $row->avoided_amount,
            MedalConditionType::ItemCount->value => (int) $row->item_count,
        ]);
    }

    public function valueOf(MedalConditionType $type): int
    {
        return $this->values[$type->value];
    }

    public function satisfies(Medal $medal): bool
    {
        return $this->valueOf($medal->condition_type) >= $medal->threshold;
    }
}
