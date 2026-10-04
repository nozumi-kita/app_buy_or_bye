<?php

namespace App\Support;

use App\Models\Medal;
use App\Models\User;
use Illuminate\Support\Collection;

final class MedalAwarder
{
    public static function awardFor(User $user): Collection
    {
        $progress = MedalProgress::for($user);
        $acquiredIds = $user->medals()->pluck('medals.id');

        $newlyAcquiredMedals = Medal::query()
            ->whereNotIn('id', $acquiredIds)
            ->orderBy('display_order')
            ->get()
            ->filter(fn (Medal $medal) => $progress->satisfies($medal))
            ->values();

        if ($newlyAcquiredMedals->isEmpty()) {
            return $newlyAcquiredMedals;
        }

        $user->medals()->attach(
            $newlyAcquiredMedals->mapWithKeys(fn (Medal $medal) => [
                $medal->id => ['acquired_at' => now()],
            ])->all()
        );

        return $newlyAcquiredMedals;
    }
}
