<?php

namespace App\Support;

use App\Models\Item;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final class ItemOwner
{
    private function __construct(
        public readonly ?int $userId,
        public readonly ?string $hashedSessionId,
    ) {}

    public static function current(): self
    {
        if ($userId = Auth::id()) {
            return new self($userId, null);
        }

        if ($hashedSessionId = GuestSession::hashedSessionId()) {
            return new self(null, $hashedSessionId);
        }

        throw new AuthenticationException;
    }

    public function items(): Builder
    {
        return $this->userId !== null
            ? Item::query()->whereNull('hashed_session_id')->where('user_id', $this->userId)
            : Item::query()->whereNull('user_id')->where('hashed_session_id', $this->hashedSessionId);
    }

    public function owns(Item $item): bool
    {
        if ($this->userId !== null) {
            return $item->user_id === $this->userId;
        }

        if ($this->hashedSessionId !== null) {
            return $item->hashed_session_id === $this->hashedSessionId;
        }

        return false;
    }
}
