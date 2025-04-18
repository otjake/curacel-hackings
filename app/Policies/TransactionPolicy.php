<?php

namespace App\Policies;

use App\Enums\AdminPermission;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any transactions.
     *
     * @param Admin $user
     *
     * @return mixed
     */
    public function viewAny(Admin $user)
    {
        return is_null($user->role) || $user->role->hasPermissionTo(AdminPermission::VIEW_TRANSACTIONS->value);
    }

    /**
     * Determine whether the user can view a single transaction.
     *
     * @param Admin $user
     *
     * @return mixed
     */
    public function view(Admin $user)
    {
        return is_null($user->role) || $user->role->hasPermissionTo(AdminPermission::VIEW_TRANSACTIONS->value);
    }
}
