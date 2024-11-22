<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MillionVisions\LaravelI18n\Extensions\BladeDirectives;

/**
 * I18nServiceProvider Class
 *
 * This service provider is responsible for bootstrapping and registering
 * the components of the Internationalization (I18n) package. It includes
 * functionalities such as publishing configuration files, registering Blade
 * extensions, and setting up custom middleware.
 */
class I18nServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * This method is called after all other service providers have been registered.
     *
     * @access public
     * @return void
     */
    public function boot(): void
    {
        $this->publishAssets();
        $this->registerCommands();
        $this->registerRouteMacros();
    }

    public function register(): void
    {
        parent::register();
        $this->registerBladeExtensions();
        $this->registerContracts();
        $this->registerMiddleware();
        #
        $this->callAfterResolving('blade.compiler', fn() => $this->registerBladeExtensions());
    }

    /**
     * Publish the package assets.
     *
     * This method publishes the assets files for the I18n package to the application's directories.
     *
     * @access public
     * @return void
     */
    public function publishAssets(): void
    {
        if (!function_exists('config_path'))
            return;

        $this->publishes([
            realpath(dirname(__DIR__)) . '/stubs/config/i18n.php' => config_path('i18n.php'),
        ], 'config');
    }

    /**
     * Register Blade extensions.
     *
     * This method defines custom Blade directives for the I18n package.
     *
     * @access protected
     * @return void
     */
    protected function registerBladeExtensions(): void
    {
        Blade::directive('alternate', [BladeDirectives::class, 'alternateUrl']);
        Blade::directive('alternates', [BladeDirectives::class, 'alternateUrls']);
        Blade::if('locale', fn(string $value) => App::getLocale() === $value);
        Blade::if('unlessLocale', fn(string $value) => App::getLocale() !== $value);
    }

    /**
     * Register the package commands.
     *
     * This method registers the Artisan commands that are available for use in the I18n package.
     *
     * @access protected
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands([
            Commands\CreateTranslationFiles::class,
        ]);
    }

    /**
     * Register the package contracts.
     *
     * This method registers the service provider's contracts with the Laravel service container.
     *
     * @access protected
     * @return void
     */
    protected function registerContracts(): void
    {
        $this->app->scoped(
            \MillionVisions\LaravelI18n\Contracts\AlternateUrlGenerator::class,
            fn() => new AlternateUrlGenerator( $this->app->make(UrlGenerator::class) )
        );
    }

    /**
     * Register middleware for the package.
     *
     * This method registers the `locale` middleware with the Laravel router.
     *
     * @access protected
     * @return void
     */
    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware('locale', Middleware\LocaleMiddleware::class);
    }

    /**
     * Register route macros for localization.
     *
     * This method checks if the Route class has a macro method and registers a
     * `localized` macro that allows routes to be prefixed with a locale parameter
     * and assigned locale middleware. The macro can be used to easily define
     * routes that require localization handling.
     *
     * @access protected
     * @return void
     */
    protected function registerRouteMacros(): void
    {
        if (!method_exists(Route::class, 'macro')) {
            return;
        }

        Route::macro('localized', function ($locale = null) {
            /** @var \Illuminate\Routing\Route $this */
            /** @var string $url */
            $url = Config::get('app.url');
            /** @var string $locale_param */
            $locale_param = Config::get('i18n.url.locale_param');
            /** @var string $param_type */
            $param_type = Config::get('i18n.url.param_type');
            /** @var non-falsy-string $middleware */
            $middleware = $locale
                ? "locale:{$locale}"
                : "locale";

            switch ($param_type) {
                case 'query_param':
                    $this->setParameter($locale_param, $locale);
                    break;
                case 'segment':
                    $this->prefix("\{{$locale_param}\}");
                    break;
                case 'subdomain':
                    $this->domain("{$locale}.{$url}");
                    break;
            }

            $this->middleware($middleware);

            return $this;
        });
    }
}
