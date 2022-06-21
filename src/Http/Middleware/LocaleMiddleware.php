<?php

namespace Lankhaar\Multilingual\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Lankhaar\Multilingual\Enum\LocaleIdentifierType;
use Lankhaar\Multilingual\Service\ConfigService;
use Lankhaar\Multilingual\Service\LocaleService;

class LocaleMiddleware
{
    public function __construct(
        protected LocaleService $localeService
    ) {}

    /**
     * Handle an incoming request to set the locale to the app.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $locale = $this->localeService->getCurrentLocaleForRequest($request);
        $request->setLocale($locale);
        app()->setLocale($locale);

        return $next($request);
    }
}
