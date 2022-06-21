<?php

namespace Lankhaar\Multilingual\Tests;

use Illuminate\Support\Facades\Route;
use Lankhaar\Multilingual\MultilingualServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            MultilingualServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $config = $app['config'];

        $config->set('multilingual.defaultLocale', 'en');
        $config->set('multilingual.availableLocales', [
            'en' => 'English',
            'nl' => 'Dutch',
        ]);

        $config->set('multilingual.localeDomains', [
            'en' => 'example.com',
            'nl' => 'example.nl',
        ]);
    }

    protected function usesTestTranslationRoutes($app)
    {
        // Set translations directory to test translations
        $this->app->bind('path.lang', function() {
            return __DIR__ . '/translations';
        });

        // Register a test route
        Route::group(['middleware' => ['web']], function () {
            Route::get('/test', function () {
                return __('test.hello');
            });
        });
    }
}
