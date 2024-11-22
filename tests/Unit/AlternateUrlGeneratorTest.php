<?php

/*
 | (c) copyright 2024 - MillionVisions
 */

namespace MillionVisions\LaravelI18n\Tests\Unit;

use MillionVisions\LaravelI18n\AlternateUrlGenerator;
use function MillionVisions\LaravelI18n\Tests\setup_query_configurations;
use function MillionVisions\LaravelI18n\Tests\setup_segment_configurations;
use function MillionVisions\LaravelI18n\Tests\setup_subdomain_configurations;

test('createAlternateUrl(): returns false for a invalid locale (with \'query\')', function () {
    setup_query_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var string|false $absolute_url */
    $absolute_url = $generator->createAlternateUrl('xx', 'example');

    expect($absolute_url)->toBeNull();
});

test('createAlternateUrl(): returns absolute url for valid locale (with \'query\')', function () {
    setup_query_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var string|false $absolute_url */
    $absolute_url = $generator->createAlternateUrl('en', 'example');

    expect($absolute_url)->toBe('http://localhost/example?lang=en');
});

test('createAlternateUrl(): returns relative url for valid locale (with \'query\')', function () {
    setup_query_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var string|false $relative_url */
    $relative_url = $generator->createAlternateUrl('en', 'example', false);

    expect($relative_url)->toBe('/example?lang=en');
});

test('createAlternateUrls(): returns array with absolute urls for available locales (with \'query\')', function () {
    setup_query_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var array<string,string> $absolute_urls */
    $absolute_urls = $generator->createAlternateUrls(route_name: 'example');

    expect($absolute_urls)->toBe([
        'en' => 'http://localhost/example?lang=en',
        'es' => 'http://localhost/example?lang=es',
        'fr' => 'http://localhost/example?lang=fr',
    ]);
});

test('createAlternateUrls(): returns array with absolute urls for valid locales (with \'query\')', function () {
    setup_query_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var array<string,string> $absolute_urls */
    $absolute_urls = $generator->createAlternateUrls(['en', 'fr'], 'example');

    expect($absolute_urls)->toBe([
        'en' => 'http://localhost/example?lang=en',
        'fr' => 'http://localhost/example?lang=fr',
    ]);
});

test('createAlternateUrl(): returns false for a invalid locale (with \'segment\')', function () {
    setup_segment_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var string|false $absolute_url */
    $absolute_url = $generator->createAlternateUrl('xx', 'example');

    expect($absolute_url)->toBeNull();
});

test('createAlternateUrl(): returns absolute url for valid locale (with \'segment\')', function () {
    setup_segment_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var string|false $absolute_url */
    $absolute_url = $generator->createAlternateUrl('en', 'example');

    expect($absolute_url)->toBeString('http://localhost/en/example');
});

test('createAlternateUrl(): returns relative url for valid locale (with \'segment\')', function () {
    setup_segment_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var string|false $relative_url */
    $relative_url = $generator->createAlternateUrl('en', 'example', false);

    expect($relative_url)->toBe('/en/example');
});

test('createAlternateUrls(): returns array with absolute urls for available locales (with \'segment\')', function () {
    setup_segment_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var array<string,string> $absolute_urls */
    $absolute_urls = $generator->createAlternateUrls(route_name: 'example');

    expect($absolute_urls)->toBe([
        'en' => 'http://localhost/en/example',
        'es' => 'http://localhost/es/example',
        'fr' => 'http://localhost/fr/example',
    ]);
});

test('createAlternateUrls(): returns array with absolute urls for valid locales (with \'segment\')', function () {
    setup_segment_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var array<string,string> $absolute_urls */
    $absolute_urls = $generator->createAlternateUrls(['en', 'fr'], 'example');

    expect($absolute_urls)->toBe([
        'en' => 'http://localhost/en/example',
        'fr' => 'http://localhost/fr/example',
    ]);
});

test('createAlternateUrl(): returns false for a invalid locale (with \'subdomain\')', function () {
    setup_subdomain_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var string|false $absolute_url */
    $absolute_url = $generator->createAlternateUrl('xx', 'example');

    expect($absolute_url)->toBeNull();
});

test('createAlternateUrl(): returns absolute url for valid locale (with \'subdomain\')', function () {
    setup_subdomain_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var string|false $absolute_url */
    $absolute_url = $generator->createAlternateUrl('en', 'example');

    expect($absolute_url)->toBeString('http://en.localhost/example');
});

test('createAlternateUrl(): returns relative url for valid locale (with \'subdomain\')', function () {
    setup_subdomain_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var string|false $relative_url */
    $relative_url = $generator->createAlternateUrl('en', 'example', false);

    expect($relative_url)->toBeNull();
});

test('createAlternateUrls(): returns array with absolute urls for available locales (with \'subdomain\')', function () {
    setup_subdomain_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var array<string,string> $absolute_urls */
    $absolute_urls = $generator->createAlternateUrls(route_name: 'example');

    expect($absolute_urls)->toBe([
        'en' => 'http://en.localhost/example',
        'es' => 'http://es.localhost/example',
        'fr' => 'http://fr.localhost/example',
    ]);
});

test('createAlternateUrls(): returns array with absolute urls for valid locales (with \'subdomain\')', function () {
    setup_subdomain_configurations();
    /** @var AlternateUrlGenerator $generator */
    $generator = app(AlternateUrlGenerator::class);
    /** @var array<string,string> $absolute_urls */
    $absolute_urls = $generator->createAlternateUrls(['en', 'fr'], 'example');

    expect($absolute_urls)->toBe([
        'en' => 'http://en.localhost/example',
        'fr' => 'http://fr.localhost/example',
    ]);
});
