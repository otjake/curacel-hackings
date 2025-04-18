<?php

namespace App\Nova\Traits;

use Illuminate\Http\Request;

trait UpdateonlyResource
{
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }
}
