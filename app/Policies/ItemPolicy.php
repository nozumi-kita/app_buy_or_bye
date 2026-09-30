<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use App\Support\GuestSession;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Item $item): Response
    {
        return $this->allowIfOwner($user, $item);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Item $item): Response
    {
        return $this->allowIfOwner($user, $item);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Item $item): Response
    {
        return $this->allowIfOwner($user, $item);
    }

    private function allowIfOwner(?User $user, Item $item): Response
    {
        if (GuestSession::isActive()) {
            return $item->session_id === GuestSession::hashedSessionId()
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        if ($user !== null) {
            return $item->user_id === $user->id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return Response::denyAsNotFound();
    }
}
