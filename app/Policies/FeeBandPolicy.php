<?php

namespace App\Policies;

use App\Enums\AdminRole;
use App\Models\Admin;
use App\Models\FeeBand;

class FeeBandPolicy extends TransactionPolicy
{
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
     * @param FeeBand $admin
     *
     * @return mixed
     */
    public function update(Admin $user, FeeBand $admin)
    {
        return is_null($user->role) || $user->role->name === AdminRole::ADMINISTRATOR->value;
    }

    public function attachAnyPayer(Admin $user, FeeBand $feeBand)
    {
        return false;
    }
}
