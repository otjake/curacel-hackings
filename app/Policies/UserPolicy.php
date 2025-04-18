<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\AdminRole;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Enums\UserRole;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        if ($user instanceof User) {
            return $user->hasRole(UserRole::SUPER_ADMIN->value);
        }

        if ($user instanceof Admin) {
            return true;
        }

        return false;
    }

    public function view($user)
    {
        if ($user instanceof User) {
            return $user->hasRole(UserRole::SUPER_ADMIN->value);
        }

        if ($user instanceof Admin) {
            return true;
        }

        return false;
    }

    public function update($user)
    {
        if ($user instanceof User) {
            return $user->hasRole(UserRole::SUPER_ADMIN->value);
        }

        if ($user instanceof Admin) {
            return true;
        }

        return false;
    }

    public function delete($user)
    {
        return $user instanceof Admin;
    }

} 
