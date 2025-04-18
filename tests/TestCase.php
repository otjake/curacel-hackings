<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;

    public function createAuthenticatedUser(?User $user = null): User
    {
        $user = $user ?? User::factory()->create();

        Sanctum::actingAs($user, ['*']);

        return $user;
    }

}
