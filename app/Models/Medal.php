<?php

namespace App\Models;

use App\Enums\MedalConditionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medal extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'condition_type' => MedalConditionType::class,
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany((User::class))
            ->withPivot('acquired_at')
            ->withTimestamps();
    }

    public function iconUrl()
    {
        return asset("images/medals/{$this->icon_key}.svg");
    }
}
