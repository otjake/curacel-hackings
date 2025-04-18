<?php

namespace App\Providers;

use App\Enums\AdminRole;
use App\Enums\AdminStatus;
use App\Models\Admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Spatie\Permission\Models\Role;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::createUserUsing(function ($command) {
            return [
                $command->ask('Name'),
                $command->ask('Email Address'),
                $command->secret('Password'),
                $command->secret('Password Confirmation'),
            ];
        }, function ($name, $email, $password, $passwordConfirmation) {
            if ($password !== $passwordConfirmation) {
                throw new \Exception('Passwords do not match');
            }

            $role = Role::where([
                'name' => AdminRole::ADMINISTRATOR->value,
                'guard_name' => 'admin',
            ])->first();

            Admin::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'status' => AdminStatus::ACTIVE,
                'role_id' => $role?->id,
            ]);
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user instanceof Admin && $user->status === AdminStatus::ACTIVE;
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
