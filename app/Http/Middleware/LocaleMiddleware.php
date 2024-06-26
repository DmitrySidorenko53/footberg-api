<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiFailResponse;
use App\Models\SupportedLocale;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = SupportedLocale::all()->pluck('locale')->toArray();

        $localeHeader = $request->header('Accept-Language');

        $currentLocale = $localeHeader ?? app()->getLocale();

        if (!in_array($currentLocale, $supportedLocales)) {
            return new ApiFailResponse([], 400, __('exceptions.unsupported_locale'));
        }

        app()->setLocale($currentLocale);

        return $next($request);
    }
}
