<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use App\Support\ItemOwner;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Item $item): Response
    {
        return $this->allowIfOwner($item);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Item $item): Response
    {
        return $this->allowIfOwner($item);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Item $item): Response
    {
        return $this->allowIfOwner($item);
    }

    private function allowIfOwner(Item $item): Response
    {
        return ItemOwner::current()->owns($item)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
