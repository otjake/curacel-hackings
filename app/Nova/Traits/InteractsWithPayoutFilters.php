<?php

namespace App\Nova\Traits;

use App\Nova\Filters\DateFrom;
use App\Nova\Filters\DateTo;
use Laravel\Nova\FilterDecoder;
use Laravel\Nova\Http\Requests\NovaRequest;

trait InteractsWithPayoutFilters
{
    protected function applyFiltersFromRequest(NovaRequest $request, $query)
    {
        $filters = (new FilterDecoder($request->query('filter')))->decodeFromBase64String();
        $filters = collect($filters)->collapse()->toArray();

        foreach ($filters as $filterClass => $filterValue) {
            if (empty($filterValue) || (is_array($filterValue) && empty(array_filter($filterValue)))) {
                continue;
            }

            if ($filterClass === 'Select:status') {
                $query->where('status', $filterValue);
            }

            if ($filterClass === DateFrom::class) {
                $query->where('created_at', '>=', $filterValue);
            }

            if ($filterClass === DateTo::class) {
                $query->where('created_at', '<=', $filterValue);
            }

            if ($filterClass === 'resource:payers:payer') {
                $query->where('payer_id', $filterValue);
            }

            if ($filterClass === 'resource:bulk-payouts:bulkPayout') {
                $query->where('bulk_payout_id', $filterValue);
            }

            if ($filterClass === 'DateTime:initiated_at') {
                if (isset($filterValue[0])) {
                    $query->where('initiated_at', '>=', $filterValue[0]);
                }

                if (isset($filterValue[1])) {
                    $query->where('initiated_at', '<=', $filterValue[1]);
                }
            }
        }

        return $query;
    }
}
