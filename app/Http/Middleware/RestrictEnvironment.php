<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictEnvironment
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$allowedEnvironments): Response
    {
        $allowedEnvironments = empty($allowedEnvironments) ? [null] : $allowedEnvironments;

        if (! in_array(app()->environment(), $allowedEnvironments)) {
            abort(404);
        }

        return $next($request);
    }
}
