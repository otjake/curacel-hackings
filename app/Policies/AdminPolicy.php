<?php

namespace App\Policies;

use App\Enums\AdminRole;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view admins.
     *
     * @param Admin $user
     *
     * @return mixed
     */
    public function view(Admin $user)
    {
        return true;
    }

    /**
     * Determine whether the user can create the admin.
     *
     * @param Admin $user
     *
     * @return mixed
     */
    public function create(Admin $user)
    {
        return is_null($user->role) || $user->role->name === AdminRole::ADMINISTRATOR->value;
    }

    /**
     * Determine whether the user can update the admin.
     *
     * @param Admin $user
     * @param Admin $admin
     *
     * @return mixed
     */
    public function update(Admin $user, Admin $admin)
    {
        return is_null($user->role) || $user->role->name === AdminRole::ADMINISTRATOR->value;
    }
}
