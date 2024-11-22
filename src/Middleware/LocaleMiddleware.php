<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use MillionVisions\LaravelI18n\Facades\I18n;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * LocaleMiddleware Class
 *
 * This middleware handles the localization of incoming HTTP requests by detecting and setting the appropriate locale.
 * It ensures that requests are routed to the correct language version of the application based on URL segments.
 * If no locale is specified, it redirects the request to the fallback locale. The middleware also validates
 * that the requested locale is among the available locales and ensures consistent language handling across the application.
 *
 * Key Functionalities:
 *   - Checks for the presence of a locale in the request URL.
 *   - Redirects to a fallback locale if no specific locale is found in the URL.
 *   - Validates the requested locale against the list of available locales.
 *   - Sets the application locale for the duration of the request, ensuring language consistency.
 *
 * Configuration:
 *   - Requires configuration values for `available_locales`, `fallback_locale`,
 *     `url.local_param`, `url.local_segment` and `url.param_type` to properly detect and handle different languages.
 *
 * Usage:
 *   Typically added to the HTTP middleware stack, this class helps in providing multi-language support
 *   by routing users to the appropriate language based on the request URL.
 */
class LocaleMiddleware
{
    /**
     * Handle an incoming request and ensure it has a valid locale.
     *
     * This method checks whether a locale is present in the incoming request's URL segment.
     * If no locale is provided, it redirects the request to the same route but with the
     * fallback locale. It also verifies that the requested locale matches the expected
     * locale or is among the available locales.
     *
     * @access public
     * @param Request                      $Request The current HTTP request instance.
     * @param Closure(Request): (Response) $next    The next middleware to be called.
     * @param string|null                  $locale  The expected locale (if specified).
     * @return Response
     */
    public function handle(Request $Request, Closure $next, ?string $locale = null): Response
    {
        /** @var string $requested_locale */
        $requested_locale = $this->getLocaleFromRequest($Request);

        // redirect to current route with fallback locale segment if no locale requested
        if ($requested_locale === null) {
            /** @var bool $auto_detect */
            $auto_detect = Config::get('i18n.auto_detect.enabled');
            /** @var string $fallback_locale */
            $fallback_locale = $auto_detect
                ? I18n::detectLocale() ?? I18n::getDefaultLocale()
                : I18n::getDefaultLocale();
            /** @var string $fallback_url */
            $fallback_url = $this->buildUrlWithLocale($Request, $fallback_locale);

            return Redirect::to($fallback_url);
        }

        /** @var array<int,string> $available_locales */
        $available_locales = Config::get('i18n.available_locales');

        if (!in_array($requested_locale, $available_locales)) {

            throw new NotFoundHttpException();
        }

        App::setLocale($requested_locale);

        return $next($Request);
    }

    /**
     * Build a URL with the specified locale.
     *
     * This method determines the appropriate URL structure based on the configuration (query parameter, segment, or subdomain)
     * and constructs a new URL using the specified locale.
     *
     * @access protected
     * @param Request $Request The current HTTP request instance.
     * @param string  $locale  The locale to include in the constructed URL.
     * @return string The newly constructed URL with the specified locale.
     */
    protected function buildUrlWithLocale(Request $Request, string $locale): string
    {
        /** @var string $param_type */
        $param_type = Config::get('i18n.url.param_type');

        return match ($param_type) {
            'query'     => $this->buildUrlWithLocaleQueryParam($Request, $locale),
            'segment'   => $this->buildUrlWithLocaleSegment($Request, $locale),
            'subdomain' => $this->buildUrlWithLocaleSubdomain($Request, $locale),
        };
    }

    /**
     * Constructs a URL using a query parameter for the locale.
     *
     * This method appends or modifies a query parameter in the URL to include the specified locale.
     * Useful for applications where the locale is managed as part of the query string.
     *
     * @access protected
     * @param Request $Request The current HTTP request instance.
     * @param string  $locale  The locale to be added as a query parameter.
     * @return string The URL with the locale included as a query parameter.
     */
    protected function buildUrlWithLocaleQueryParam(Request $Request, string $locale): string
    {
        /** @var string $locale_param */
        $locale_param = Config::get('i18n.url.locale_param');
        /** @var string[] $query_params */
        $query_params = $Request->query();

        $query_params[$locale_param] = $locale;

        return $Request->fullUrlWithQuery($query_params);
    }

    /**
     * Constructs a URL with the specified locale segment.
     *
     * This method takes the current request and modifies its URL by replacing or inserting
     * the locale segment at the appropriate position. It allows for consistent URL structures
     * across different locales, ensuring that the application redirects correctly when a
     * fallback locale is needed.
     *
     * @access protected
     * @param Request $Request The current HTTP request instance.
     * @param string  $locale  The locale that should be added to or replace the current URL segment.
     * @return string The newly constructed URL with the specified locale.
     */
    protected function buildUrlWithLocaleSegment(Request $Request, string $locale): string
    {
        /** @var int $locale_segment_index */
        $locale_segment_index = Config::get('i18n.url.locale_segment') - 1;
        /** @var string[] $segments */
        $segments = $Request->segments();

        array_splice($segments, $locale_segment_index, 0, $locale);

        return $Request->root() . '/' . implode('/', $segments);
    }

    /**
     * Constructs a URL using a subdomain for the locale.
     *
     * This method builds a URL that uses the locale as a subdomain. If the locale matches the
     * default locale, no subdomain is added. Useful for applications that distinguish locales
     * using subdomains (e.g., "en.example.com" for English).
     *
     * @access protected
     * @param Request $Request The current HTTP request instance.
     * @param string  $locale  The locale to be used as a subdomain.
     * @return string The URL with the locale as a subdomain.
     */
    protected function buildUrlWithLocaleSubdomain(Request $Request, string $locale): string
    {

        return str_replace(
            $Request->getHost(),
            "{$locale}." . $Request->getHost(),
            $Request->fullUrl()
        );
    }

    /**
     * Retrieve the locale from the current request based on the configuration.
     *
     * This method inspects the request to determine the locale based on the configured method (query parameter, URL segment, or subdomain).
     *
     * @access protected
     * @param Request $Request The current HTTP request instance.
     * @return string|null The locale extracted from the request, or null if none is found.
     */
    protected function getLocaleFromRequest(Request $Request): ?string
    {
        /** @var string */
        $param_type = Config::get('i18n.url.param_type');

        return match ($param_type) {
            'query'     => $this->getLocaleFromRequestQueryParam($Request),
            'segment'   => $this->getLocaleFromRequestSegment($Request),
            'subdomain' => $this->getLocaleFromRequestSubdomain($Request),
            default     => null
        };
    }

    /**
     * Get the locale from the query parameters of the request.
     *
     * This method retrieves the locale from a specified query parameter.
     * If the query parameter is not present, it returns null.
     *
     * @access protected
     * @param Request $Request The current HTTP request instance.
     * @return string|null The locale extracted from the query parameters, or null if not found.
     */
    protected function getLocaleFromRequestQueryParam(Request $Request): ?string
    {
        /** @var array<int,string> $available_locales */
        $available_locales = Config::get('i18n.available_locales');
        /** @var string $locale_param */
        $locale_param = Config::get('i18n.url.locale_param');
        /** @var string $locale */
        $locale = $_GET[$locale_param] ?? null;

        return strlen($locale) === 2 || in_array($locale, $available_locales)
            ? $locale
            : null;
    }

    /**
     * Get the locale from the URL segment of the request.
     *
     * This method checks a specific segment in the URL to determine the locale.
     * The segment position is configured and can be adjusted as needed.
     *
     * @access protected
     * @param Request $Request The current HTTP request instance.
     * @return string|null The locale extracted from the URL segment, or null if not found.
     */
    protected function getLocaleFromRequestSegment(Request $Request): ?string
    {
        /** @var array<int,string> $available_locales */
        $available_locales = Config::get('i18n.available_locales');
        /** @var string $locale_segment */
        $locale_segment = Config::get('i18n.url.locale_segment');
        /** @var string $locale */
        $locale = $Request->segment($locale_segment);

        return strlen($locale) === 2 || in_array($locale, $available_locales)
            ? $locale
            : null;
    }

    /**
     * Get the locale from the subdomain of the request.
     *
     * This method examines the subdomain part of the request URL to extract the locale.
     * It assumes the locale is represented as a subdomain (e.g., "en" in "en.example.com").
     *
     * @access protected
     * @param Request $Request The current HTTP request instance.
     * @return string|null The locale extracted from the subdomain, or null if none is found.
     */
    protected function getLocaleFromRequestSubdomain(Request $Request): ?string
    {
        /** @var array<int,string> $available_locales */
        $available_locales = Config::get('i18n.available_locales');
        /** @var string $host */
        $host = $Request->getHost();
        /** @var string $locale */
        $locale = explode('.', $host)[0];

        return strlen($locale) === 2 || in_array($locale, $available_locales)
            ? $locale
            : null;
    }
}
