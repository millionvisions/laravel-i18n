<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Facades\Config;
use MillionVisions\LaravelI18n\I18nServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class UnitTestCase extends TestCase
{
    use InteractsWithViews;

    protected function getPackageProviders($app)
    {
        return [
            I18nServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.paths', [__DIR__.'/resources/views']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('app.locale', 'de');
        Config::set('i18n.available_locales', ['de', 'en', 'es', 'fr']);
        Config::set('i18n.default_locale', 'de');
        Config::set('i18n.fallback_locale', 'de');
    }
}
