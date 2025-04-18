<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function getItemsPerPage(Request $request)
    {
        $itemsPerPage = 20;

        if (! is_null($request->per_page) && in_array($request->per_page, ['10', '20', '50', '100'])) {
            $itemsPerPage = $request->per_page;
        }

        return $itemsPerPage;
    }
}
