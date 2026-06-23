<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {// get browser or client preferred language
        $locale = $request->header('Accept-Language');

        if (!in_array($locale, ['ar', 'en'])) {
            $locale = config('app.locale');
        }
        
        app()->setLocale($locale);
        return $next($request);
    }
}
