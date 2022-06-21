<?php

namespace Lankhaar\Multilingual\Tests\Feature\LocaleIdentifierType;

use Lankhaar\Multilingual\Service\LocaleService;
use Lankhaar\Multilingual\Tests\TestCase;

class SessionIdentifierTypeTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('multilingual.localeIdentifierType', 'session');
    }

    /** @test */
    public function doesSessionUpdateAfterLocaleSwitch()
    {
        $expectedLocale = 'nl';

        $response = $this->call('get', '/multilingual/switch/' . $expectedLocale);

        $response->assertSessionHas(LocaleService::LOCALE_SESSION_KEY, $expectedLocale);
    }

    /**
     * @define-route usesTestTranslationRoutes
     * @test
     */
    public function doesLocaleUpdateAfterLocaleSwitch()
    {
        $defaultLocale = 'en';
        $alternativeLocale = 'nl';
        $translations = [
            $defaultLocale => 'Hello world',
            $alternativeLocale => 'Hallo wereld',
        ];

        // Check default locale
        $defaultLocaleResponse = $this->withSession([
            LocaleService::LOCALE_SESSION_KEY => $defaultLocale
        ])->call('get', '/test/');

        // Check with alternative locale
        $alternativeLocaleResponse = $this->withSession([
            LocaleService::LOCALE_SESSION_KEY => $alternativeLocale
        ])->call('get', '/test/');

        $this->assertEquals($translations[$defaultLocale], $defaultLocaleResponse->content());
        $this->assertEquals($translations[$alternativeLocale], $alternativeLocaleResponse->content());
    }
}
