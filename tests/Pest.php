<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(UnitTestCase::class)->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeTest', function (?string $path = null) {
    return $this->toBe(test_app_url($path));
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function setup_query_configurations()
{
    Config::set('i18n.url.locale_param', 'lang');
    Config::set('i18n.url.param_type', 'query');

    Route::getRoutes()
        ->add(
            Route::get('example?lang={lang}', function () {
                return 'Example';
            })
                ->name('example')
        );

    Route::getRoutes()
        ->add(
            Route::get('alternate?lang={lang}', function () {
                return view('directive-alternate')->render();
            })
                ->name('alternate')
        );
}

function setup_segment_configurations()
{
    Config::set('i18n.url.locale_param', 'locale');
    Config::set('i18n.url.locale_segment', 1);
    Config::set('i18n.url.param_type', 'segment');

    Route::getRoutes()
        ->add(
            Route::get('/{locale}/example', function () {
                return 'Example';
            })
                ->name('example')
        );
}

function setup_subdomain_configurations()
{
    Config::set('i18n.url.locale_param', 'locale');
    Config::set('i18n.url.param_type', 'subdomain');

    Route::getRoutes()
        ->add(
            Route::get('/example', function () {
                return 'Example';
            })
                ->name('example')
                ->domain('{locale}.' . Config::get('app.url'))
        );
}
