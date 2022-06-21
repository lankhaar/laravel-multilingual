<?php

namespace Lankhaar\Multilingual\Routing;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollectionInterface;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Support\Traits\Macroable;
use Lankhaar\Multilingual\Enum\LocaleIdentifierType;
use Lankhaar\Multilingual\Service\ConfigService;

use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Illuminate\Routing\UrlGenerator as OriginalUrlGenerator;
use Lankhaar\Multilingual\Service\LocaleUrlService;

class UrlGenerator extends OriginalUrlGenerator implements UrlGeneratorContract
{
    use InteractsWithTime, Macroable;

    /**
     * Create a new URL Generator instance.
     */
    public function __construct(
        protected ConfigService $configService,
        /** @var OriginalUrlGenerator */
        protected UrlGeneratorContract $decoratedUrlGenerator,
        protected LocaleUrlService $localeUrlService
    ) {}

    /**
     * @inheritDoc
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        $url = $this->decoratedUrlGenerator->route($name, $parameters, $absolute);
        $request = \request();

        if ($this->configService->getLocaleIdentifierType() !== LocaleIdentifierType::Url) {
            return $url;
        }

        $locale = $request->attributes->get('REQUEST_URL_LOCALE', $this->configService->getDefaultLocale());

        if ($absolute) {
            $parsedUrl = parse_url($url);
            return sprintf('%s://%s/%s%s',
                $parsedUrl['scheme'] ?? 'http',
                $parsedUrl['host'],
                $locale,
                $parsedUrl['path'] ?? '/'
            );
        } else {
            $parsedUrl = parse_url($url);
            return sprintf('/%s%s',
                $locale,
                $parsedUrl['path'] ?? '/'
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function full()
    {
        return $this->decoratedUrlGenerator->full(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->decoratedUrlGenerator->current(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function previous($fallback = false)
    {
        return $this->decoratedUrlGenerator->previous(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function previousPath($fallback = false)
    {
        return $this->decoratedUrlGenerator->previousPath(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function to($path, $extra = [], $secure = null)
    {
        return $this->decoratedUrlGenerator->to(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function secure($path, $parameters = [])
    {
        return $this->decoratedUrlGenerator->secure(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function asset($path, $secure = null)
    {
        return $this->decoratedUrlGenerator->asset(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function secureAsset($path)
    {
        return $this->decoratedUrlGenerator->secureAsset(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function assetFrom($root, $path, $secure = null)
    {
        return $this->decoratedUrlGenerator->assetFrom(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function formatScheme($secure = null)
    {
        return $this->decoratedUrlGenerator->formatScheme(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function signedRoute($name, $parameters = [], $expiration = null, $absolute = true)
    {
        return $this->decoratedUrlGenerator->signedRoute(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true)
    {
        return $this->decoratedUrlGenerator->temporarySignedRoute(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function hasValidSignature(Request $request, $absolute = true, array $ignoreQuery = [])
    {
        return $this->decoratedUrlGenerator->hasValidSignature(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function hasValidRelativeSignature(Request $request, array $ignoreQuery = [])
    {
        return $this->decoratedUrlGenerator->hasValidRelativeSignature(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function hasCorrectSignature(Request $request, $absolute = true, array $ignoreQuery = [])
    {
        return $this->decoratedUrlGenerator->hasCorrectSignature(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function signatureHasNotExpired(Request $request)
    {
        return $this->decoratedUrlGenerator->signatureHasNotExpired(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function toRoute($route, $parameters, $absolute)
    {
        return $this->decoratedUrlGenerator->toRoute(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function action($action, $parameters = [], $absolute = true)
    {
        return $this->decoratedUrlGenerator->action(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function formatParameters($parameters)
    {
        return $this->decoratedUrlGenerator->formatParameters(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function formatRoot($scheme, $root = null)
    {
        return $this->decoratedUrlGenerator->formatRoot(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function format($root, $path, $route = null)
    {
        return $this->decoratedUrlGenerator->format(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function isValidUrl($path)
    {
        return $this->decoratedUrlGenerator->isValidUrl(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function defaults(array $defaults)
    {
        $this->decoratedUrlGenerator->defaults(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function getDefaultParameters()
    {
        return $this->decoratedUrlGenerator->getDefaultParameters(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function forceScheme($scheme)
    {
        $this->decoratedUrlGenerator->forceScheme(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function forceRootUrl($root)
    {
        $this->decoratedUrlGenerator->forceRootUrl(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function formatHostUsing(Closure $callback)
    {
        return $this->decoratedUrlGenerator->formatHostUsing(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function formatPathUsing(Closure $callback)
    {
        return $this->decoratedUrlGenerator->formatPathUsing(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function pathFormatter()
    {
        return $this->decoratedUrlGenerator->pathFormatter(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function getRequest()
    {
        return $this->decoratedUrlGenerator->getRequest(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function setRequest(Request $request)
    {
        $this->decoratedUrlGenerator->setRequest(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function setRoutes(RouteCollectionInterface $routes)
    {
        return $this->decoratedUrlGenerator->setRoutes(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function setSessionResolver(callable $sessionResolver)
    {
        return $this->decoratedUrlGenerator->setSessionResolver(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function setKeyResolver(callable $keyResolver)
    {
        return $this->decoratedUrlGenerator->setKeyResolver(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function getRootControllerNamespace()
    {
        return $this->decoratedUrlGenerator->getRootControllerNamespace(...func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function setRootControllerNamespace($rootNamespace)
    {
        return $this->decoratedUrlGenerator->setRootControllerNamespace(...func_get_args());
    }
}
