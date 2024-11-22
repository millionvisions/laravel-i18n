# millionvisions/laravel-i18n

[![License: MIT](https://poser.pugx.org/laravel/framework/license.svg)](https://opensource.org/licenses/MIT)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/millionvisions/laravel-i18n.svg?style=flat-square)](https://packagist.org/packages/millionvisions/laravel-i18n)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/abagayev/laravel-migration-shortcuts/tests.yml)](https://github.com/millionvisions/laravel-i18n/actions)

## Overview

The `millionvisions/laravel-i18n` package provides a simple way to manage translations and localization in Laravel
applications. It offers features such as middleware for locale handling, Blade directives for generating alternate URLs,
and easy generation of translation files.

## Installation

1. Install the package via composer:

```sh
composer require millionvisions/laravel-i18n
```

2. Publish the configuration file to `config/i18n.php`:

```sh
php artisan vendor:publish --provider="MillionVisions\I18n\I18nServiceProvider"
```

3. Add the I18nServiceProvider to the providers array in `bootstrap/providers.php`:

```php
return [
    //...
    MillionVisions\LaravelI18n\I18nServiceProvider::class,
    //...
];
```

## Create translation files

To generate translation files based on your application's defined keys, you can use the following artisan commands.

### i18n:create-translation-files

```sh
php artisan i18n:create-translation-files
```

This command scans your application for translation keys and creates corresponding PHP files in the configured language
directory, ensuring that your translations are structured and ready for use.

### i18n:seed

```sh
php artisan i18n:seed
```

This command acts as an alias for the previous command and can be used interchangeably to achieve the same result.

## Use the locale middleware

The package includes middleware that helps manage locale preferences in your application. It ensures that routes are
accessible based on the provided locale parameter.

### To define a group of routes that requires locale handling, you can use the following example:

```php
Route::group(
    ['prefix' => '{locale}', 'middleware' => 'locale'], 
    function () {
        // Define your localized routes here
    }
);
```

In this example, the prefix option specifies that the locale will be part of the URL. For instance, accessing /en or /de
will route to the appropriate controller actions. The middleware ensures that the application checks the validity of the
locale before proceeding.

### Allowing only specific locales:

```php
Route::group(
    ['prefix' => '{locale}', 'middleware' => 'locale:en'],
    function() {
        // Define your localized routes here
    }
);
```

In this case, the locale:en middleware restricts access to this route group, allowing only English (en) as a valid
locale. Other locales would result in an error or redirection based on your middleware's logic.

### Using the `localized` macro

```php
Route::localized()->group(function () {
    // Define your localized routes here
});
```

The localized macro provides a convenient way to define a group of routes that automatically handle locale prefixes and
middleware. It streamlines the process of creating localized routes without repetitive code.

## Use the blade directives

The package introduces custom Blade directives to facilitate the generation of alternate URLs for your application.

### Alternate URL
```blade
@alternate('de')
```

This directive generates an HTML link tag that points to the equivalent page in the specified locale (in this case,
German). It includes the appropriate hreflang attribute to help search engines understand language relationships.

### Alternate URLS
```blade
@alternates()
```

This directive automatically generates links for all defined locales, making it easier to manage multilingual SEO by
ensuring that all language versions of a page are properly linked.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
