<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route as RouteFacade;

/**
 * AlternateUrlGenerator Class
 *
 * This class is responsible for generating alternate URLs for different
 * locales within the application. It provides methods to create URLs
 * based on the current route while changing the locale parameter.
 */
class AlternateUrlGenerator implements Contracts\AlternateUrlGenerator
{
    public function __construct(
        protected UrlGenerator $UrlGenerator
    ) {}

    /**
     * Create an alternate URL for a given locale.
     *
     * This method generates a URL for the specified locale, either for a
     * named route or for the current route if no name is provided. It updates
     * the 'locale' parameter in the route to reflect the desired locale.
     *
     * @acccess public
     * @param string      $locale     The locale for which to generate the URL.
     * @param string|null $route_name The name of the route (optional). If not provided, the current route is used.
     * @param bool        $absolute   Whether to generate an absolute URL (default: true).
     * @return string|null            The generated URL for the specified locale or false.
     */
    public function createAlternateUrl(string $locale, ?string $route_name = null, bool $absolute = true): ?string
    {
        /** @var array<int,string> $available_locales */
        $available_locales = Config::get('i18n.available_locales');
        /** @var string $param_type */
        $param_type = Config::get('i18n.url.param_type');

        if ( !in_array($locale, $available_locales) || $param_type === 'subdomain' && $absolute === false ) {

            return null;
        }

        /** @var string $locale_param */
        $locale_param = Config::get('i18n.url.locale_param');
        /** @var string $name */
        $name = $route_name ?? RouteFacade::currentRouteName();
        /** @var array<string,string> $params */
        $params = RouteFacade::current()
            ? [$locale_param => $locale, ...RouteFacade::current()->parameters()]
            : [$locale_param => $locale];

        return $this->UrlGenerator->route($name, $params, $absolute);
    }

    /**
     * Create alternate URLs for all available locales.
     *
     * This method generates an array of alternate URLs for all locales
     * defined in the configuration, except for the current locale. It uses
     * the `createAlternateUrl` method to generate each URL.
     *
     * @access public
     * @param array<int,string>|null $locales    An optional array of locale codes for which to generate URLs.
     *                                           If empty, all available locales from the configuration will be used.
     * @param string|null            $route_name The name of the route (optional). If not provided, the current route is used.
     * @return array<string,string>              An associative array where the keys are locale codes and the values
     *                                           are the corresponding URLs.
     */
    public function createAlternateUrls(?array $locales = [], ?string $route_name = null): array
    {
        /** @var string $current_locale */
        $current_locale = App::getLocale();
        /** @var string[] $alternate_locales */
        $alternate_locales = empty($locales)
            ? Config::get('i18n.available_locales')
            : $locales;
        /** @var string[] $alternate_urls */
        $alternate_urls = [];

        foreach ($alternate_locales as $alternate_locale) {
            if ($alternate_locale === $current_locale) {
                continue;
            }

            $alternate_urls[$alternate_locale] = $this->createAlternateUrl($alternate_locale, $route_name);
        }

        return $alternate_urls;
    }
}
