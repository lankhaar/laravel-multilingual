<?php

namespace Lankhaar\Multilingual\Tests\Feature\LocaleIdentifierType;

use Lankhaar\Multilingual\Service\LocaleService;
use Lankhaar\Multilingual\Tests\TestCase;

class UrlIdentifierTypeTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('multilingual.localeIdentifierType', 'url');
    }

    /** @test */
    public function doesUriChangeAfterLocaleSwitch()
    {
        $defaultLocale = 'en';
        $alternativeLocale = 'nl';

        $defaultLocaleResponse = $this->call('get', '/multilingual/switch/' . $defaultLocale);
        $alternativeLocaleResponse = $this->call('get', '/multilingual/switch/' . $alternativeLocale);

        // Assert that domain doesn't change but URI does
        $defaultLocaleResponse->assertStatus(302);
        $defaultLocaleResponse->assertRedirectContains('example.com/en');
        $alternativeLocaleResponse->assertStatus(302);
        $alternativeLocaleResponse->assertRedirectContains('example.com/nl');
    }

    /**
     * @define-route usesTestTranslationRoutes
     * @test
     */
    public function doesLocaleCorrespondDomain()
    {
        $defaultLocale = 'en';
        $alternativeLocale = 'nl';
        $translations = [
            $defaultLocale => 'Hello world',
            $alternativeLocale => 'Hallo wereld',
        ];

        // Check default locale
        $defaultLocaleResponse = $this->call('get', 'http://example.com/en/test/');

        // Check with alternative locale
        $alternativeLocaleResponse = $this->call('get', 'http://example.nl/nl/test/');

        $this->assertEquals($translations[$defaultLocale], $defaultLocaleResponse->content());
        $this->assertEquals($translations[$alternativeLocale], $alternativeLocaleResponse->content());
    }
}
