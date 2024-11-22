<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n\Service;

use Illuminate\Support\Facades\Config;

/**
 * I18nService Class
 *
 * This class provides methods to retrieve localization configurations,
 * including available locales, the default locale, and the fallback locale.
 */
class I18nService
{
    /**
     * Detect the current locale.
     *
     * This method determines the current locale setting by inspecting the configuration
     * for the preferred auto-detection method. It can detect locale based on the user's
     * browser settings or IP address. If no specific method is configured, the system will
     * default to using the application's predefined default locale.
     *
     * @access public
     * @return string|null The detected or default locale code.
     */
    public function detectLocale(): ?string
    {
        /** @var string $method */
        $method = Config::get('i18n.auto_detect.method');

        return match ($method) {
            'browser' => $this->detectLocaleFromBrowser(),
            default   => $this->getDefaultLocale(),
        };
    }

    /**
     * Detect the locale based on the user's browser settings.
     *
     * This method reads the 'Accept-Language' header sent by the user's browser to determine
     * the preferred language. It checks the first two characters of the language code (e.g., 'en' for English)
     * and matches it against the list of available locales configured in the application.
     * If a match is found, it returns the locale; otherwise, it returns null.
     *
     * @access public
     * @return string|null The locale extracted from the browser's 'Accept-Language' header, or null if no match is found.
     */
    public function detectLocaleFromBrowser(): ?string
    {
        /** @var string[] $available_locales */
        $available_locales = Config::get('i18n.available_locales');
        /** @var string $accept_language */
        $accept_language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        return in_array($accept_language, $available_locales) ? $accept_language : null;
    }

    /**
     * Get the available locales.
     *
     * This method retrieves the list of available locales as defined in the application's configuration file.
     * The locales are typically used for managing translations and localization throughout the application.
     *
     * @access public
     * @return array<int,string> An array of available locale codes.
     */
    public function getAvailableLocales(): array
    {
        return Config::get('i18n.available_locales');
    }

    /**
     * Get the default locale.
     *
     * This method retrieves the default locale set in the application's configuration file.
     * The default locale is used when no specific locale is provided.
     *
     * @access public
     * @return string The default locale code.
     */
    public function getDefaultLocale(): string
    {
        return Config::get('i18n.default_locale');
    }

    /**
     * Get the fallback locale.
     *
     * This method retrieves the fallback locale defined in the application's configuration file.
     * The fallback locale is used when a translation is not available in the current or requested locale.
     *
     * @access public
     * @return string The fallback locale code.
     */
    public function getFallbackLocale(): string
    {
        return Config::get('i18n.fallback_locale');
    }
}
