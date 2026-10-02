<?php

namespace App\Support;

use App\Enums\ItemStatus;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

final class ItemTotals
{
    /**
     * @param  array<string, array{count: int, amount: int}>  $thisWeek
     * @param  array<string, array{count: int, amount: int}>  $allTime
     */
    private function __construct(
        private readonly array $thisWeek,
        private readonly array $allTime,
    ) {}

    public static function for(ItemOwner $itemOwner): self
    {
        $startOfThisWeek = self::startOfThisWeek();

        $rows = $itemOwner->items()
            ->toBase()
            ->select('status')
            ->selectRaw('COUNT(*) FILTER (WHERE status_changed_at >= ?) AS this_week_count', [$startOfThisWeek])
            ->selectRaw('COALESCE(SUM(price) FILTER (WHERE status_changed_at >= ?), 0) AS this_week_amount', [$startOfThisWeek])
            ->selectRaw('COUNT(*) AS all_time_count')
            ->selectRaw('COALESCE(SUM(price), 0) AS all_time_amount')
            ->groupBy('status')
            ->get();

        $thisWeek = [];
        $allTime = [];

        foreach (ItemStatus::cases() as $itemStatus) {
            $thisWeek[$itemStatus->value] = ['count' => 0, 'amount' => 0];
            $allTime[$itemStatus->value] = ['count' => 0, 'amount' => 0];
        }

        foreach ($rows as $row) {
            $thisWeek[$row->status] = [
                'count' => (int) $row->this_week_count,
                'amount' => (int) $row->this_week_amount,
            ];
            $allTime[$row->status] = [
                'count' => (int) $row->all_time_count,
                'amount' => (int) $row->all_time_amount,
            ];
        }

        return new self($thisWeek, $allTime);
    }

    public function thisWeekCount(ItemStatus $itemStatus): int
    {
        return $this->thisWeek[$itemStatus->value]['count'];
    }

    public function thisWeekAmount(ItemStatus $itemStatus): int
    {
        return $this->thisWeek[$itemStatus->value]['amount'];
    }

    public function allTimeCount(ItemStatus $itemStatus): int
    {
        return $this->allTime[$itemStatus->value]['count'];
    }

    public function allTimeAmount(ItemStatus $itemStatus): int
    {
        return $this->allTime[$itemStatus->value]['amount'];
    }

    private static function startOfThisWeek(): CarbonImmutable
    {
        return CarbonImmutable::now(config('app.display_timezone'))
            ->startOfWeek(CarbonInterface::MONDAY)
            ->utc();
    }
}
