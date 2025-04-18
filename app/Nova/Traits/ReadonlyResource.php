<?php

namespace App\Nova\Traits;

use Illuminate\Http\Request;

trait ReadonlyResource
{
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function authorizedToView(Request $request)
    {
        return true;
    }

    public static function authorizedToViewAny(Request $request)
    {
        return true;
    }
}
