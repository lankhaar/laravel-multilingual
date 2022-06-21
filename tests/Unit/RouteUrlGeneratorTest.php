<?php

namespace Lankhaar\Multilingual\Tests\Unit;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Lankhaar\Multilingual\Tests\TestCase;

class RouteUrlGeneratorTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('multilingual.localeIdentifierType', 'url');

        parent::getEnvironmentSetUp($app);
    }

    /** @test */
    public function routeFunctionAdjustsUri()
    {
        $urlTypeRoute = route('switch-locale', ['locale' => 'en']);

        $this->app['config']->set('multilingual.localeIdentifierType', 'session');
        $sessionTypeRoute = route('switch-locale', ['locale' => 'en']);

        // Assert that URI is "clean"
        $this->assertEquals('http://example.com/multilingual/switch/en', $sessionTypeRoute);

        // Assert that URI contains locale
        $this->assertEquals('http://example.com/en/multilingual/switch/en', $urlTypeRoute);
    }
}
