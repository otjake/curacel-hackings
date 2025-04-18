<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Models\Admin;
use App\Models\User;

class ApprovalWorkflowPolicy extends SystemConfigurationPolicy
{
    /**
     * Determine whether the user can create approval workflows.
     *
     * @param User|Admin $user
     *
     * @return mixed
     */
    public function create($user)
    {
        if ($user instanceof User) {
            return $user->hasPermissionTo(UserPermission::CONFIGURE_WORKFLOW->value, 'api');
        }

        if ($user instanceof Admin) {
            return true;
        }

        return false;
    }

    public function viewAny($user)
    {
        if ($user instanceof User) {
            return $user->hasPermissionTo(UserPermission::CONFIGURE_WORKFLOW->value, 'api');
        }

        if ($user instanceof Admin) {
            return true;
        }

        return false;
    }
}
