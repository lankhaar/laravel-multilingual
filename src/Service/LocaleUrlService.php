<?php

namespace Lankhaar\Multilingual\Service;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocaleUrlService
{
    protected array $localeUrlCaches = [];

    public function __construct(
        protected ConfigService $configService,
        protected Router $router
    ) {}

    /**
     * @param Request $request
     *
     * @return bool
     *
     * @throws \Lankhaar\Multilingual\Exception\InvalidConfigException
     */
    public function requestHasLocaleUrl(Request $request): bool
    {
        // We cache (non persistent) the check per URL to prevent any extra resource
        // usage on runtime if method is called multiple times per URL
        if (key_exists($request->getRequestUri(), $this->localeUrlCaches)) {
            return $this->localeUrlCaches[$request->getRequestUri()];
        }

        // If URI is found in routes, we know it's valid, and it won't be a virtual URI
        try {
            $this->router->getRoutes()->match($request);

            return $this->localeUrlCaches[$request->getRequestUri()] = false;
        } catch (NotFoundHttpException $exception) {
        }

        // Return true if URL start with any of the configured locales
        foreach ($this->configService->getLocales() as $locale => $language) {
            if (str_starts_with($request->getRequestUri() . '/', "/$locale/")) {
                return $this->localeUrlCaches[$request->getRequestUri()] = true;
            }
        }

        // Return false if no locale was configured
        return $this->localeUrlCaches[$request->getRequestUri()] = false;
    }

    /**
     * Get the original route URI
     *
     * E.g. /en/about -> /about
     *
     * @param Request $request
     *
     * @return string
     */
    public function getOriginalUriForRequest(Request $request): string
    {
        // Make sure we don't mess up an already original URL
        if (!$this->requestHasLocaleUrl($request)) {
            return $request->getRequestUri();
        }

        // Get rid of everything before the first slash
        $uriParts = explode('/', ltrim($request->getRequestUri(), '/'));
        array_shift($uriParts);

        return '/' . implode('/', $uriParts);
    }

    /**
     * Get the locale from request URI
     * E.g. /en/about will return 'en'
     * Will return null when no locale in URL provided
     *
     * @param Request $request
     *
     * @return null|string
     */
    public function getLocaleFromRequestUri(Request $request): ?string
    {
        // Return null if no locale provided
        if (!$this->requestHasLocaleUrl($request)) {
            return null;
        }

        // Split URL on slashes
        $uriParts = explode('/', ltrim($request->getRequestUri(), '/'));

        // Return first item
        return array_shift($uriParts);
    }
}
