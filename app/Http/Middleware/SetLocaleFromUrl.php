<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleFromUrl
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $segments = $request->segments();
        $locale = $segments[0] ?? null;
        $supportedLocales = array_values(config('app.supported_locales', ['ru', 'lv']));

        if ($locale && in_array($locale, $supportedLocales, true)) {
            App::setLocale($locale);
            $request->setLocale($locale);
        } else {
            App::setLocale(config('app.locale', 'en'));
        }

        return $next($request);
    }
}
