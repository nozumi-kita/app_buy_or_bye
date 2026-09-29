<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Item $item): Response
    {
        return $this->owns($user, $item);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Item $item): Response
    {
        return $this->owns($user, $item);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Item $item): Response
    {
        return $this->owns($user, $item);
    }

    private function owns(User $user, Item $item): Response
    {
        return $user->id === $item->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
