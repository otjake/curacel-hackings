<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class SystemConfigurationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any system configurations.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function viewAny($user)
    {
        if ($user instanceof User) {
            return $user->hasAnyPermission([
                UserPermission::MANAGE_INTEGRATION->value,
                UserPermission::CONFIGURE_WORKFLOW->value,
            ]);
        }

        if ($user instanceof Admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view a single system configuration.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function view($user)
    {
        if ($user instanceof User) {
            return $user->hasAnyPermission([
                UserPermission::MANAGE_INTEGRATION->value,
                UserPermission::CONFIGURE_WORKFLOW->value,
            ]);
        }

        if ($user instanceof Admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update system configurations.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function update($user)
    {
        if ($user instanceof User) {
            return $user->hasAnyPermission([
                UserPermission::MANAGE_INTEGRATION->value,
                UserPermission::CONFIGURE_WORKFLOW->value,
            ]);
        }

        if ($user instanceof Admin) {
            return true;
        }

        return false;
    }
}
