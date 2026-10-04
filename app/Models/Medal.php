<?php

namespace App\Models;

use App\Enums\MedalConditionType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\JoinClause;

class Medal extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'condition_type' => MedalConditionType::class,
            'acquired_at' => 'datetime',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('acquired_at')
            ->withTimestamps();
    }

    public function iconUrl()
    {
        return asset("images/medals/{$this->icon_key}.svg");
    }

    #[Scope]
    protected function withAcquiredAtFor(Builder $query, User $user): void
    {
        $query->leftJoin('medal_user', function (JoinClause $join) use ($user) {
            $join->on('medal_user.medal_id', '=', 'medals.id')
                ->where('medal_user.user_id', $user->id);
        })->select('medals.*', 'medal_user.acquired_at');
    }

    public function isAcquired(): bool
    {
        return $this->acquired_at !== null;
    }
}
