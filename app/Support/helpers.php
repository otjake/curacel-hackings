<?php

use App\Actions\GetBanks;
use App\Enums\DashboardAction;
use App\Enums\TransactionChannel;
use App\Models\MobileMoneyOperator;
use App\Models\Payer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

if (! function_exists('get_login_route')) {
    /**
     * Get the login route for the current environment.
     *
     * @return string
     */
    function get_login_route(): string
    {
        return app()->environment('local') ? 'login' : 'sso.login';
    }
}
