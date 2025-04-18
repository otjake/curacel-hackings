<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Laravel\Pennant\Feature;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @param Request $request
     *
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @param Request $request
     *
     * @return array
     */
    public function share(Request $request): array
    {
        $payerDetails = null;
        $payer = $request->user()?->payer?->load('kycInformation.state', 'kycInformation.country');

        if ($payer) {
            $payerDetails = [
                'name' => $payer->name,
                'email' => $payer->email,
                'country_code' => $payer->country_code,
                'currency' => $payer->currency,
                'transaction_channel' => $payer->transaction_channel,
                'kyc_information' => $payer->kycInformation?->only([
                    'phone_number',
                    'address_line_one',
                    'address_line_two',
                    'city',
                    'state',
                    'country',
                ]),
                'logo_url' => $payer->logo_url,
                'current_overdue_fee' => $payer->current_overdue_fee,
                'features' => Feature::all(),
            ];
        }

        return array_merge(parent::share($request), [
            'response' => [
                'data' => $request->session()->get('data', []),
            ],
            'base_url' => config('app.url'),
            'payer' => $payerDetails,
            'roles' => $request->user()?->getRoleNames(),
            'permissions' => $request->user()?->getAllPermissions(),
            'flash' => [
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }

    /**
     * Resolves and prepares validation errors in such
     * a way that they are easier to use client-side.
     *
     * @return object
     */
    public function resolveValidationErrors(Request $request)
    {
        if (! $request->hasSession()) {
            return (object) [];
        }

        if ($request->session()->has('error')) {
            // format it the same way an axios error response would be formatted.
            return (object) [
                'response' => [
                    'data' => [
                        'message' => $request->session()->get('error'),
                    ],
                ],
            ];
        }

        return parent::resolveValidationErrors($request);
    }
}
