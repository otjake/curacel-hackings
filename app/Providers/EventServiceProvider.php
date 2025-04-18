<?php

namespace App\Providers;

use App\Actions\Payment\ProcessPaymentReversal;
use App\Actions\PaymentCollection\DistributeCollectionWebhook;
use App\Events\Invoice\InvoiceCancelled;
use App\Events\Invoice\InvoiceCreated;
use App\Events\Invoice\InvoiceUpdated;
use App\Events\Payment\PaymentCollected;
use App\Events\Payment\PaymentFailed;
use App\Events\Payment\PaymentInitiated;
use App\Events\Payment\PaymentVerified;
use App\Listeners\Invoice\SendCancellationNotificationToCustomer;
use App\Listeners\Invoice\SendNotificationToCustomer;
use App\Listeners\Invoice\SendUpdateNotificationToCustomer;
use App\Listeners\Payment\LogPaymentInitiatedActivity;
use App\Listeners\Payment\PaymentEventSubscriber;
use App\Listeners\Payment\SendCollectedPaymentNotification;
use App\Listeners\Payment\SendPaymentToProcessor;
use App\Listeners\Payment\UpdatePaymentCollectionStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Sage\SageExtendSocialite::class.'@handle',
            \SocialiteProviders\FusionAuth\FusionAuthExtendSocialite::class.'@handle',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
