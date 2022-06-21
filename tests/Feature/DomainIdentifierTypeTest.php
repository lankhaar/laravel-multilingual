<?php

namespace Lankhaar\Multilingual\Tests\Feature\LocaleIdentifierType;

use Lankhaar\Multilingual\Service\LocaleService;
use Lankhaar\Multilingual\Tests\TestCase;

class DomainIdentifierTypeTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('multilingual.localeIdentifierType', 'domain');
    }

    /** @test */
    public function doesDomainChangeAfterLocaleSwitch()
    {
        $defaultLocale = 'en';
        $alternativeLocale = 'nl';

        $defaultLocaleResponse = $this->call('get', '/multilingual/switch/' . $defaultLocale);
        $alternativeLocaleResponse = $this->call('get', '/multilingual/switch/' . $alternativeLocale);

        $defaultLocaleResponse->assertStatus(302);
        $defaultLocaleResponse->assertRedirectContains('example.com');

        $alternativeLocaleResponse->assertStatus(302);
        $alternativeLocaleResponse->assertRedirectContains('example.nl');
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
        $defaultLocaleResponse = $this->call('get', 'http://example.com/test/');

        // Check with alternative locale
        $alternativeLocaleResponse = $this->call('get', 'http://example.nl/test/');

        $this->assertEquals($translations[$defaultLocale], $defaultLocaleResponse->content());
        $this->assertEquals($translations[$alternativeLocale], $alternativeLocaleResponse->content());
    }
}
