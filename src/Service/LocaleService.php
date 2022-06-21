<?php

namespace Lankhaar\Multilingual\Service;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Lankhaar\Multilingual\Enum\LocaleIdentifierType;
use Lankhaar\Multilingual\Exception\InvalidConfigException;
use Lankhaar\Multilingual\Exception\UnmappedLocaleDomain;

class LocaleService
{
    public const LOCALE_SESSION_KEY = 'multilingual-locale';

    public function __construct(
        protected ConfigService $configService,
        protected LocaleUrlService $localeUrlService
    )
    {}

    /**
     * @param Request $request
     *
     * @return string
     *
     * @throws InvalidConfigException
     */
    public function getCurrentLocaleForRequest(Request $request): string
    {
        switch ($this->configService->getLocaleIdentifierType()) {
            case LocaleIdentifierType::Session:
                if (Session::has(self::LOCALE_SESSION_KEY)) {
                    return Session::get(self::LOCALE_SESSION_KEY);
                }

            case LocaleIdentifierType::Domain:
                return $this->getLocaleForDomain($request);

            case LocaleIdentifierType::Url:
                return $this->getLocaleForUrl($request);
        }

        return $this->configService->getDefaultLocale();
    }

    /**
     * @todo Add support for LocaleIdentifierType::Url
     *
     * @param string $locale
     *
     * @return RedirectResponse
     *
     * @throws InvalidConfigException
     * @throws UnmappedLocaleDomain
     */
    public function handleLocaleSwitch(string $locale): RedirectResponse
    {
        switch ($this->configService->getLocaleIdentifierType()) {
            case LocaleIdentifierType::Session:
                Session::put(self::LOCALE_SESSION_KEY, $locale);
                return Redirect::back();

            case LocaleIdentifierType::Domain:
                $redirectUrl = $this->getDomainForLocale($locale);
                break;

            case LocaleIdentifierType::Url:
                $redirectUrl = $this->getUrlForLocale($locale);
                break;
        }

        // phpstan thinks refirectUrl can be undefined, but this is not possible
        // due to the use of strict type enum in getLocaleIdentifierType() return
        /** @phpstan-ignore-next-line */
        return Redirect::to($redirectUrl);
    }

    /**
     * @param string $locale
     *
     * @return string
     *
     * @throws InvalidConfigException
     * @throws UnmappedLocaleDomain
     */
    protected function getDomainForLocale(string $locale): string
    {
        if (!key_exists($locale, $localeDomains = $this->configService->getLocaleDomains())) {
            throw new UnmappedLocaleDomain('No domain configured for locale ' . $locale);
        }

        return $this->getFullyQualifiedUrl($localeDomains[$locale]);
    }

    /**
     * @param Request $request
     *
     * @return string
     *
     * @throws InvalidConfigException
     */
    protected function getLocaleForDomain(Request $request): string
    {
        $localeDomains = $this->configService->getLocaleDomains();

        if (!$locale = array_search($request->getHost(), $localeDomains)) {
            return $this->configService->getDefaultLocale();
        }

        return $locale;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    protected function getLocaleForUrl(Request $request): string
    {
        return $request->attributes->get('REQUEST_URL_LOCALE', $this->configService->getDefaultLocale());
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    protected function getUrlForLocale(string $locale): string
    {
        $previousRequestUri = parse_url(Session::previousUrl())['path'] ?? '/';
        return sprintf('/%s%s', $locale, $previousRequestUri);
    }

    /**
     * @param string $domain
     *
     * @return string
     */
    protected function getFullyQualifiedUrl(string $domain): string
    {
        $parsedUrl = parse_url($domain);
        $scheme = $parsedUrl['scheme'] ?? 'http';
        $host = $parsedUrl['host'] ?? $domain;

        return sprintf('%s://%s', $scheme, $host);
    }
}
