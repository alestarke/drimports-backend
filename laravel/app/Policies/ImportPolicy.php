<?php

namespace App\Policies;

use App\Models\User;

class ImportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Import $import): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Import $import): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Import $import): bool
    {
        return $user->is_admin;
    }

    public function restore(User $user, Import $import): bool
    {
        return false; 
    }

    public function forceDelete(User $user, Import $import): bool
    {
        return false;
    }
}
