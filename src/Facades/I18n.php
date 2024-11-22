<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n\Facades;

use Illuminate\Support\Facades\Facade;
use MillionVisions\LaravelI18n\Service\I18nService;

/**
 * I18n facade Class
 *
 * This facade provides a simple and convenient way to access the `I18nService` within the Laravel application.
 * By using this facade, developers can easily interact with the core internationalization (i18n) functionalities
 * without directly needing to instantiate the service. It acts as a static interface to the underlying
 * service layer, allowing for streamlined and readable code.
 *
 * Key Functionalities:
 *   - Acts as a proxy to the `I18nService` class, enabling easy access to its methods.
 *   - Simplifies usage of localization features, such as translation key management and locale handling.
 *
 * Usage:
 *   The `I18n` facade can be used to quickly call any public method from the `I18nService`.
 *   This will internally route the call to the `I18nService` and execute the corresponding method.
 *
 * @method static string|null detectLocale() Detect the locale based on the request.
 * @method static string|null detectLocaleFromBrowser() Detect the locale based on the browser settings.
 * @method static string getAvailableLocales() Get the available locales.
 * @method static string getDefaultLocale() Get the default locale.
 * @method static string getFallbackLocale() Get the fallback locale.
 *
 * @see \MillionVisions\LaravelI18n\Service\I18nService
 */
class I18n extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * This method is used to specify the service or class that the facade should resolve from
     * the Laravel service container. It returns the name of the class or binding that the facade
     * proxies, which in this case is the `I18nService`. When the facade is accessed, it automatically
     * routes calls to the `I18nService` instance, enabling seamless use of its methods.
     *
     * @access protected
     * @static
     * @return string The class name or binding key used to resolve the service.
     */
    protected static function getFacadeAccessor(): string
    {
        return I18nService::class;
    }
}
