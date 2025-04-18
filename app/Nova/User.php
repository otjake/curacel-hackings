<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Application Users';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            BelongsTo::make('Payer')
                ->nullable()
                ->searchable(),

            DateTime::make('Last Login At', 'last_login_at')->exceptOnForms(),

            Text::make('Registered on FusionAuth', function () {
                return $this->fusionauth_id ? 'Yes' : 'No';
            })->onlyOnIndex(),

            DateTime::make('Creation Date', 'created_at')->exceptOnForms(),

            MorphToMany::make('Roles', 'roles')
                ->help("Search roles by names, e.g. 'super_admin', 'payer_developer', 'sales', 'finance', 'operations' ")
                ->searchable(),

            MorphToMany::make('Permissions', 'permissions')
                ->searchable()
                ->help(
                    'Search permissions by names: '.
                    "'global_access', 'user_management', 'assign_roles', ".
                    "'configure_workflow', 'configure_system', 'manage_integration', ".
                    "'access_analytics', 'customer_support', 'transactions_viewer', ".
                    "'manage_transaction', 'process_refund', 'generate_financial_report', ".
                    "'monitor_settlement', 'manage_financial_setting', 'monitor_transaction', ".
                    "'investigate_dispute', 'monitor_system_performance', 'manage_customer', ".
                    "'access_sales_report', 'onboard_customer', 'report_analytics'"
                ),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
