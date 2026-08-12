<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Api\ApiAllowedIp;

class ApiAllowIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIp = ApiAllowedIp::where('ip', $request->ip())->first();

        if (!$allowedIp) {
            return abort(404);
        }

        return $next($request);
    }
}
