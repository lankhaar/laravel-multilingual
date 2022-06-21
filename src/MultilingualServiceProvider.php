<?php

namespace Lankhaar\Multilingual;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Events\Routing;
use Illuminate\Routing\RouteCollectionInterface;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Lankhaar\Multilingual\Enum\LocaleIdentifierType;
use Lankhaar\Multilingual\Http\Controllers;
use Lankhaar\Multilingual\Http\Middleware\LocaleMiddleware;
use Lankhaar\Multilingual\Routing\ResolveRealRoute;
use Lankhaar\Multilingual\Routing\UrlGenerator;
use Lankhaar\Multilingual\Service\ConfigService;
use Lankhaar\Multilingual\Service\LocaleService;
use Lankhaar\Multilingual\Service\LocaleUrlService;

class MultilingualServiceProvider extends ServiceProvider
{
    protected ConfigService $configService;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Kernel $kernel): void
    {
        $this->configService = $this->app->make(ConfigService::class);

        // Publish package configs
        $this->publishes([
            dirname(__DIR__) . '/config/multilingual.php' => config_path('multilingual.php'),
        ]);

        // Register routes
        $this->loadRoutesFrom(dirname(__DIR__) . '/web/routes.php');

        // Register views
        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'multilingual');

        // Register http middlewares
        $this->registerMiddlewares($kernel);

        // Register controllers
        $this->registerControllers();

        // Register blade components
        $this->registerBladeComponents();

        // Register event listeners
        $this->registerEventListeners();

        // Register decorators
        $this->registerDecorator();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register services to DI container
        $this->app->make(LocaleService::class);
    }

    /**
     * @return void
     */
    protected function registerControllers(): void
    {
        $this->app->make(Controllers\MultilingualController::class);
    }

    /**
     * @return void
     */
    protected function registerBladeComponents(): void
    {
        Blade::directive('multilingualSwitcher', function () {
            return "<?php echo view('multilingual::language-switcher')->render(); ?>";
        });
    }

    /**
     * @param Kernel $kernel
     *
     * @return void
     */
    protected function registerMiddlewares(Kernel $kernel): void
    {
        $kernel->appendMiddlewareToGroup('web', LocaleMiddleware::class);
    }

    /**
     * @return void
     */
    protected function registerEventListeners(): void
    {
        if ($this->configService->getLocaleIdentifierType() === LocaleIdentifierType::Url) {
            Event::listen(
                Routing::class,
                [ResolveRealRoute::class, 'handleRerouteIfRequestUriIsVirtual']
            );
        }
    }

    /**
     * @return void
     */
    protected function registerDecorator(): void
    {
        if ($this->configService->getLocaleIdentifierType() === LocaleIdentifierType::Url) {
            $decoratedClass = $this->app->make('url');
            $this->app->bind('url', fn () => new UrlGenerator(
                $this->configService,
                $decoratedClass,
                $this->app->make(LocaleUrlService::class)
            ));
        }
    }
}
