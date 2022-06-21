<?php

namespace Lankhaar\Multilingual\Routing;

use Illuminate\Http\Request;
use Illuminate\Routing\Events\Routing;
use Lankhaar\Multilingual\Service\ConfigService;
use Lankhaar\Multilingual\Service\LocaleUrlService;

class ResolveRealRoute
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        protected ConfigService $configService,
        protected LocaleUrlService $localeUrlService
    ) {}

    /**
     * Handle the event.
     * Reroute the request if the requested URI is a virtual URI with a locale key in it.
     *
     * @param Routing $event
     *
     * @return void
     */
    public function handleRerouteIfRequestUriIsVirtual(Routing $event): void
    {
        $request = $event->request;

        // We only want to manipulate GET requests
        if ($request->getMethod() !== 'GET') {
            return;
        }

        // Skip request if URI doesn't start with /{locale}/
        if (!$this->localeUrlService->requestHasLocaleUrl($request)) {
            return;
        }

        // Manipulate request URI
        $request->server->set('REQUEST_URI', $this->localeUrlService->getOriginalUriForRequest($request));
        $request->attributes->set('REQUEST_URL_LOCALE', $this->localeUrlService->getLocaleFromRequestUri($request));

        // Swap "virtual url" request with the "real url" request
        Request::createFrom($request, $request);
    }
}
