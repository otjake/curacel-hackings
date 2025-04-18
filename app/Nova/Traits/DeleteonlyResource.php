<?php

namespace App\Nova\Traits;

use Illuminate\Http\Request;

trait DeleteonlyResource
{
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}
