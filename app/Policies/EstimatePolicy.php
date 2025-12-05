<?php

namespace App\Policies;

use App\Models\Estimate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EstimatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Estimate $estimate): bool
    {
        return $user->id === $estimate->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Estimate $estimate): bool
    {
        return $user->id === $estimate->user_id;
    }

    public function delete(User $user, Estimate $estimate): bool
    {
        return $user->id === $estimate->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Estimate $estimate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Estimate $estimate): bool
    {
        return false;
    }
}

