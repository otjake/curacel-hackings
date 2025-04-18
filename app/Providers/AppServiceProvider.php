<?php

namespace App\Providers;

use App\Enums\TransactionType;
use App\Facades\Auth;
use App\Models\ApiCredential;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Inspector\Laravel\Facades\Inspector;
use Lorisleiva\Actions\Facades\Actions;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inspector::beforeFlush(function ($inspector) {
            $inspector->currentTransaction()
                ->host
                //use the environment variable to set the hostname
                ->hostname = Config::get('app.env');
        });

        $this->registerBladeDirectives();

        Actions::registerCommands();
    }


    private function registerBladeDirectives(): void
    {
        Blade::directive('currency', function (string $amount, string $currency = 'NGN') {
            return "<?php echo \Illuminate\Support\Number::currency($amount, \"$currency\"); ?>";
        });
    }
}
