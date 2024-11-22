<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |
    | This option allows you to specify the locales that your application will
    | support. Users can switch between these languages based on the settings
    | configured. The default locale and the available locales can be set via
    | environment variables.
    |
    */
    'available_locales' => explode('|', env('APP_LOCALES', 'de|en')),

    /*
    |--------------------------------------------------------------------------
    | Default and Fallback Locales
    |--------------------------------------------------------------------------
    |
    | The default locale is the language your application will use if no other
    | locale is specified by the user. The fallback locale will be used when
    | the selected locale does not have a corresponding translation available.
    | Both can be configured via the environment variables below.
    |
    */
    'default_locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Automatic Locale Detection
    |--------------------------------------------------------------------------
    |
    | This option enables automatic detection of the user's locale based on their
    | browser settings or IP address. When enabled, the application will attempt
    | to determine the user's preferred language and set the locale accordingly.
    | You can prioritize 'browser', and if detection fails, the default locale
    | will be used.
    |
    | Supported methods: "browser"
    |
    */
    'auto_detect' => [
        'enabled' => env('I18N_AUTO_DETECT_LOCALE', true),
        'method' => env('I18N_AUTO_DETECT_METHOD', 'browser'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Seeder Configuration
    |--------------------------------------------------------------------------
    |
    | The following settings configure how the translation seeder will operate.
    | You can specify the root directory for scanning translation strings, the
    | file extensions to include, and directories to ignore. The generated
    | translation files will be saved in the specified target directory.
    |
    */
    'seeder' => [
        'base_path' => base_path(),
        'file_extensions' => ['.php'],
        'ignore_directories' => ['node_modules', 'vendor'],
        'target_directory' => lang_path(),
    ],

    /*
    |--------------------------------------------------------------------------
    | URL Locale Handling
    |--------------------------------------------------------------------------
    |
    | These settings define how the locale is determined from the URL. You can
    | choose to use query parameters, URL segments, or subdomains for locale
    | identification. The `param_type` determines the method, while the other
    | settings control specific details like parameter names and segment index.
    |
    | Supported `param_type` values:
    |   - "query": Locale is passed as a query parameter (e.g., ?locale=en).
    |   - "segment": Locale is set as a URL segment (e.g., /en/page).
    |   - "subdomain": Locale is set as a subdomain (e.g., en.example.com).
    |
    */
    'url' => [
        'locale_param' => env('I18N_LOCALE_PARAM', 'locale'),
        'locale_segment' => env('I18N_LOCALE_SEGMENT', 1),
        'param_type' => env('I18N_LOCALE_PARAM_TYPE', 'segment'),
    ],

];
