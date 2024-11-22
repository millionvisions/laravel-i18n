<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

if (!function_exists('alternate_url')) {
    /**
     * Generate an alternate URL for a given locale.
     *
     * @param string      $locale
     * @param string|null $route_name
     * @return string|null
     */
    function alternate_url(string $locale, ?string $route_name): ?string
    {
        return app(\MillionVisions\LaravelI18n\AlternateUrlGenerator::class)
            ->createAlternateUrl($locale, $route_name);
    }
}

if (!function_exists('alternate_urls')) {

    /**
     * Generate alternate URLs for all available locales.
     *
     * @param array       $locales
     * @param string|null $route_name
     * @return array<string,string>
     */
    function alternate_urls(array $locales = [], ?string $route_name = null): array
    {
        return app(\MillionVisions\LaravelI18n\AlternateUrlGenerator::class)
            ->createAlternateUrls($locales, $route_name);
    }
}
