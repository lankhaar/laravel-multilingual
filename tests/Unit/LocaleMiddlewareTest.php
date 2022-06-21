<?php

namespace Lankhaar\Multilingual\Tests\Unit;

use Illuminate\Http\Request;
use Lankhaar\Multilingual\Http\Middleware\LocaleMiddleware;
use Lankhaar\Multilingual\Service\LocaleService;
use Lankhaar\Multilingual\Tests\TestCase;

class LocaleMiddlewareTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->localeService = $this->app->make(LocaleService::class);
    }

    /** @test */
    public function doesMiddlewareSetLocaleCorrectlyForSession()
    {
        $this->app['config']->set('multilingual.localeIdentifierType', 'session');

        $defaultLocale = 'en';
        $expectedLocale = 'nl';
        $request = new Request;
        $this->session([
            LocaleService::LOCALE_SESSION_KEY => $expectedLocale,
        ]);

        $this->assertEquals($defaultLocale, app()->getLocale());
        $this->assertEquals($defaultLocale, $request->getLocale());

        (new LocaleMiddleware($this->localeService))->handle($request, function ($request) use ($expectedLocale) {
            $this->assertEquals($expectedLocale, app()->getLocale());
            $this->assertEquals($expectedLocale, $request->getLocale());
        });
    }

    /** @test */
    public function doesMiddlewareSetLocaleCorrectlyForDomain()
    {
        // Setup request for dutch domain
        $request = new Request(
            server: [
                'HTTP_HOST' => 'example.nl',
            ]
        );
        $defaultLocale = 'en';
        $expectedLocale = 'nl';

        $this->app['config']->set('multilingual.localeIdentifierType', 'domain');

        $this->assertEquals($defaultLocale, app()->getLocale());
        $this->assertEquals($defaultLocale, $request->getLocale());

        (new LocaleMiddleware($this->localeService))->handle($request, function ($request) use ($expectedLocale) {
            $this->assertEquals($expectedLocale, app()->getLocale());
            $this->assertEquals($expectedLocale, $request->getLocale());
        });
    }

    /** @test */
    public function doesMiddlewareSetLocaleCorrectlyForUrl()
    {
        // Setup request for dutch domain
        $request = new Request(
            attributes: [
                'REQUEST_URL_LOCALE' => 'nl',
            ]
        );
        $defaultLocale = 'en';
        $expectedLocale = 'nl';

        $this->app['config']->set('multilingual.localeIdentifierType', 'url');

        $this->assertEquals($defaultLocale, app()->getLocale());
        $this->assertEquals($defaultLocale, $request->getLocale());

        (new LocaleMiddleware($this->localeService))->handle($request, function ($request) use ($expectedLocale) {
            $this->assertEquals($expectedLocale, app()->getLocale());
            $this->assertEquals($expectedLocale, $request->getLocale());
        });
    }
}
