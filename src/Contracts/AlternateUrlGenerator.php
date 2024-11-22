<?php

namespace MillionVisions\LaravelI18n\Contracts;

interface AlternateUrlGenerator
{
    /**
     * Create an alternate URL for a given locale.
     *
     * @access public
     * @param string      $locale
     * @param string|null $route_name
     * @return string|null
     */
    public function createAlternateUrl(string $locale, ?string $route_name = null): ?string;

    /**
     * Create alternate URLs for all available locales.
     *
     * @access public
     * @param array<int,string>|null $locales
     * @param string|null $route_name
     * @return array<string,string>
     */
    public function createAlternateUrls(?array $locales, ?string $route_name = null): array;
}
