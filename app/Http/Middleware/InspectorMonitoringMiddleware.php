<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InspectorMonitoringMiddleware extends InspectorOctaneMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $_SERVER['REQUEST_METHOD'] = $request->getMethod();
        $_SERVER['REQUEST_URI'] = $request->getRequestUri();
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/'.$request->getProtocolVersion();
        $_SERVER['HTTP_HOST'] = $request->getHost();
        $_SERVER['REMOTE_ADDR'] = $request->getClientIp();
        $_SERVER['SERVER_NAME'] = $request->server('SERVER_NAME');
        $_SERVER['SERVER_PORT'] = $request->getPort();
        $_SERVER['QUERY_STRING'] = $request->getQueryString();
        $_SERVER['SCRIPT_NAME'] = $request->server('SCRIPT_NAME');

        return parent::handle($request, $next);
    }
}
