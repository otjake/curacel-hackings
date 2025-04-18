<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApiCredentialPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return $this->checkPermissions($user, [
            UserPermission::MANAGE_INTEGRATION->value,
            UserPermission::MONITOR_SYSTEM_PERFORMANCE->value,
            UserPermission::CONFIGURE_SYSTEM->value,
        ]);
    }

    public function view($user)
    {
        return $this->checkPermissions($user, [
            UserPermission::MANAGE_INTEGRATION->value,
            UserPermission::MONITOR_SYSTEM_PERFORMANCE->value,
            UserPermission::CONFIGURE_SYSTEM->value,
        ]);
    }

    public function update($user)
    {
        return $this->checkPermissions($user, [
            UserPermission::MANAGE_INTEGRATION->value,
            UserPermission::MONITOR_SYSTEM_PERFORMANCE->value,
            UserPermission::CONFIGURE_SYSTEM->value,
        ]);
    }

    public function create($user)
    {
        return $this->checkPermissions($user, [
            UserPermission::MANAGE_INTEGRATION->value,
            UserPermission::MONITOR_SYSTEM_PERFORMANCE->value,
            UserPermission::CONFIGURE_SYSTEM->value,
        ]);
    }

    private function checkPermissions($user, array $requiredPermissions): bool
    {
        if ($user instanceof User) {
            $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

            $hasPermission = false;
            foreach ($requiredPermissions as $permission) {
                if (in_array($permission, $userPermissions, true)) {
                    $hasPermission = true;
                    break;
                }
            }

            return $hasPermission;
        }

        if ($user instanceof Admin) {
            return true;
        }

        return false;
    }
}
