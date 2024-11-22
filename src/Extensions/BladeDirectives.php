<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n\Extensions;

use MillionVisions\LaravelI18n\Contracts\AlternateUrlGenerator;

/**
 * BladeDirectives Class
 *
 * This class provides Blade directives for generating alternate URLs
 * for localization purposes. It utilizes the UrlGenerator to create
 * alternate URLs based on the provided locale.
 */
class BladeDirectives
{
    /**
     * Generate an alternate URL for a specified locale.
     *
     * This method creates an HTML link element for an alternate URL in the
     * specified locale using the UrlGenerator. It returns a PHP echo statement
     * that can be directly used in Blade templates to insert the link tag.
     *
     * @access public
     * @static
     * @param string $locale The locale for which to generate the alternate URL.
     * @return string The PHP echo statement for the link element.
     */
    public static function alternateUrl(string $locale): string
    {
        // Remove any single quotes from the locale string.
        $locale = str_replace(['\''], '', $locale);
        /** @var string $url */
        $url = app(AlternateUrlGenerator::class)
            ->createAlternateUrl($locale);

        return "<?php echo(\"" . self::createAlternateLinkTag($url, $locale) . "\") ?>";
    }

    /**
     * Generate alternate URLs for all available locales.
     *
     * This method retrieves an array of alternate URLs for each available
     * locale using the UrlGenerator. It generates individual alternate URL
     * links for each locale and returns them as a single PHP echo statement
     * that can be directly used in Blade templates.
     *
     * @access public
     * @static
     * @param array<int,string> $locales An optional array of locale codes for which to generate URLs.
     *                                   If empty, all available locales from the configuration will be used.
     * @return string The PHP echo statement for all link elements.
     */
    public static function alternateUrls(array $locales = []): string
    {
        /** @var array<string,string> $urls */
        $urls = app(AlternateUrlGenerator::class)
            ->createAlternateUrls($locales);
        /** @var array<int,string> $alternate_links */
        $alternate_links = [];

        foreach ($urls as $locale => $url) {
            $alternate_links[] = self::createAlternateLinkTag($url, $locale);
        }

        return "<?php echo \"" . implode('', $alternate_links) . "\" ?>";
    }

    /**
     * Create an HTML link tag for an alternate URL.
     *
     * This method generates an HTML link tag with the specified URL and locale,
     * formatted according to the standard for alternate localization links.
     *
     * @access protected
     * @static
     * @param string $url    The URL to include in the link tag.
     * @param string $locale The locale to specify in the hreflang attribute.
     * @return string The formatted HTML link tag as a string.
     */
    protected static function createAlternateLinkTag(string $url, string $locale): string
    {
        return sprintf(
            '<link href="%1$s" hreflang="%2$s">',
            $url,
            $locale
        );
    }
}
