<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get Accept-Language header
        $locale = $request->header('Accept-Language');

        // Normalize language (en-US → en)
        if ($locale) {
            $locale = substr($locale, 0, 2);
        }

        // Validate supported languages
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = config('app.locale', 'ar');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
