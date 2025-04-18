<?php

namespace App\Facades;

use App\Traits\SetsAuthenticatedPayer;
use Illuminate\Support\Facades\Auth as LaravelAuthFacade;

class Auth extends LaravelAuthFacade
{
    use SetsAuthenticatedPayer;
}
