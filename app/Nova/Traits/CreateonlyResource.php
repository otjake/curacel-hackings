<?php

namespace App\Nova\Traits;

use Illuminate\Http\Request;

trait CreateonlyResource
{
    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}
