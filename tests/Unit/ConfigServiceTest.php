<?php

namespace Lankhaar\Multilingual\Tests\Unit;

use Illuminate\Contracts\Config\Repository;
use Lankhaar\Multilingual\Enum\LocaleIdentifierType;
use Lankhaar\Multilingual\Exception\InvalidConfigException;
use Lankhaar\Multilingual\Service\ConfigService;
use Lankhaar\Multilingual\Tests\TestCase;

class ConfigServiceTest extends TestCase
{
    protected ConfigService $configService;
    protected Repository $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = $this->app['config'];
        $this->configService = $this->app->make(ConfigService::class);
    }

    /** @test */
    public function configServiceReturnsCorrectConfigValue()
    {
        $this->assertEquals('en', $this->configService->getDefaultLocale());

        $this->config->set('multilingual.defaultLocale', 'nl');

        $this->assertEquals('nl', $this->configService->getDefaultLocale());
    }

    /** @test */
    public function configServiceChecksTypeOfUserConfiguredData()
    {
        // Happy flow: data should be string
        $this->config->set('multilingual.defaultLocale', 'en');
        $this->assertTrue(is_string($this->configService->getDefaultLocale()));

        // Unhappy flow (array):
        $this->config->set('multilingual.defaultLocale', ['en']);
        $this->assertThrows(function () {
            $this->configService->getDefaultLocale();
        }, InvalidConfigException::class);

        // Unhappy flow (int):
        $this->config->set('multilingual.defaultLocale', 1);
        $this->assertThrows(function () {
            $this->configService->getDefaultLocale();
        }, InvalidConfigException::class);
    }

    /** @test */
    public function localeIdentifierTypeConfigToEnumConversion()
    {
        // Test session type
        $this->config->set('multilingual.localeIdentifierType', 'session');
        $this->assertEquals(LocaleIdentifierType::Session, $this->configService->getLocaleIdentifierType());

        // Test domain type
        $this->config->set('multilingual.localeIdentifierType', 'domain');
        $this->assertEquals(LocaleIdentifierType::Domain, $this->configService->getLocaleIdentifierType());

        // Test url type
        $this->config->set('multilingual.localeIdentifierType', 'url');
        $this->assertEquals(LocaleIdentifierType::Url, $this->configService->getLocaleIdentifierType());

        // Test if any unknown type will throw exception
        $this->config->set('multilingual.localeIdentifierType', 'unkownType');
        $this->assertThrows(function () {
            $this->configService->getLocaleIdentifierType();
        }, InvalidConfigException::class);
    }
}
