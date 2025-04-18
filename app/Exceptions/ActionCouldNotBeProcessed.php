<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * This exception class serves the purpose of serving human-readable exceptions of errors
 * occurring in actions based on a failed check/validation/issues with user input.
 */
class ActionCouldNotBeProcessed extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return back()->with('error', $this->getMessage());
    }
}
