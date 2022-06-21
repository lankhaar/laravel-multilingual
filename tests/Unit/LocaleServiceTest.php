<?php

namespace Lankhaar\Multilingual\Tests\Unit;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Lankhaar\Multilingual\Service\LocaleService;
use Lankhaar\Multilingual\Tests\TestCase;

class LocaleServiceTest extends TestCase
{
    protected Repository $config;
    protected LocaleService $localeService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = $this->app['config'];
        $this->localeService = $this->app->make(LocaleService::class);
    }

    /** @test */
    public function sessionUpdatesOnLocaleSwitchWithSessionType()
    {
        $switchToLocale = 'en';
        $this->config->set('multilingual.localeIdentifierType', 'session');

        // Session driver booting
        $sessionStore = \Mockery::mock('Illuminate\Session\Store');
        Session::shouldReceive('driver')->andReturn($sessionStore);

        // Session should get 1 update and then read previous URL for redirecting back
        Session::shouldReceive('previousUrl')->andReturn(\Mockery::any());
        Session::shouldReceive('put')
            ->once()
            ->with(LocaleService::LOCALE_SESSION_KEY, $switchToLocale)
        ;

        $this->localeService->handleLocaleSwitch($switchToLocale);
    }

    /** @test */
    public function correctRedirectOnLocaleSwitchWithDomainType()
    {
        $this->config->set('multilingual.localeIdentifierType', 'domain');

        $localeSwitchENResponse = $this->localeService->handleLocaleSwitch('en');
        $localeSwitchNLResponse = $this->localeService->handleLocaleSwitch('nl');

        $this->assertInstanceOf(RedirectResponse::class, $localeSwitchENResponse);
        $this->assertInstanceOf(RedirectResponse::class, $localeSwitchNLResponse);

        $this->assertEquals('http://example.com', $localeSwitchENResponse->getTargetUrl());
        $this->assertEquals('http://example.nl', $localeSwitchNLResponse->getTargetUrl());
    }

    /** @test */
    public function correctRedirectOnLocaleSwitchWithUrlType()
    {
        $this->config->set('multilingual.localeIdentifierType', 'url');

        $localeSwitchENResponse = $this->localeService->handleLocaleSwitch('en');
        $localeSwitchNLResponse = $this->localeService->handleLocaleSwitch('nl');

        $this->assertInstanceOf(RedirectResponse::class, $localeSwitchENResponse);
        $this->assertInstanceOf(RedirectResponse::class, $localeSwitchNLResponse);

        $this->assertEquals('http://example.com/en', $localeSwitchENResponse->getTargetUrl());
        $this->assertEquals('http://example.com/nl', $localeSwitchNLResponse->getTargetUrl());
    }
}
