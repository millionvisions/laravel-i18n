<?php

namespace MillionVisions\LaravelI18n\Tests\Unit;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use MillionVisions\LaravelI18n\Middleware\LocaleMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function MillionVisions\LaravelI18n\Tests\setup_query_configurations;
use function MillionVisions\LaravelI18n\Tests\setup_segment_configurations;
use function MillionVisions\LaravelI18n\Tests\setup_subdomain_configurations;

test('handle(): redirects to default locale if no locale is specified in the URL (with \'query\' - without \'auto_detect.enabled\')', function () {
    setup_query_configurations();
    Config::set('i18n.auto_detect.enabled', false);

    /** @var Request $Request */
    $Request = Request::create('/example');
    /** @var Response $response */
    $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
        return $request;
    });

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())->toBe('http://localhost/example?lang=de');
});

test('handle(): redirects to client locale if no locale is specified in the URL (with \'query\' - with \'auto_detect.enabled\')', function () {
    setup_query_configurations();
    Config::set('i18n.auto_detect.enabled', true);
    Config::set('i18n.auto_detect.method', 'browser');

    /** @var Request $Request */
    $Request = Request::create('/example');
    // Simulate a request with 'Accept-Language' header set to 'es-es,es;q=0.5'
    $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es-es,es;q=0.5';
    /** @var Response $response */
    $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
        return $request;
    });

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())->toBe('http://localhost/example?lang=es');
});

test('handle(): redirects to default locale if no locale is specified in the URL and client locale is not available (with \'query\' - with \'auto_detect.enabled\')', function () {
    setup_query_configurations();
    Config::set('i18n.auto_detect.enabled', true);
    Config::set('i18n.auto_detect.method', 'browser');

    /** @var Request $Request */
    $Request = Request::create('/example');
    // Simulate a request with 'Accept-Language' header set to 'es-es,es;q=0.5'
    $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'xx-xx,xx;q=0.5';
    /** @var Response $response */
    $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
        return $request;
    });

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())->toBe('http://localhost/example?lang=de');
});

test('handle(): throws NotFoundHttpException wrong locale is specified in the URL (with \'query\' - without \'auto_detect.enabled\')', function () {
    setup_query_configurations();
    Config::set('i18n.auto_detect.enabled', false);

    try {
        /** @var Request $Request */
        $Request = Request::create('/example?lang=xx', 'GET', ['lang' => 'xx']);
        // Simulate a request with 'Accept-Language' header set to 'es-es,es;q=0.5'
        $_GET['lang'] = 'xx';
        /** @var Response $response */
        $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
            return $request;
        });
    }
    catch (NotFoundHttpException $e) {
        expect($e)->toBeInstanceOf(NotFoundHttpException::class);
    }
});

test('handle(): redirects to default locale if no locale is specified in the URL (with \'segment\' - without \'auto_detect.enabled\')', function () {
    setup_segment_configurations();
    Config::set('i18n.auto_detect.enabled', false);

    /** @var Request $Request */
    $Request = Request::create('/example');
    /** @var Response $response */
    $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
        return $request;
    });

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())->toBe('http://localhost/de/example');
});

test('handle(): redirects to client locale if no locale is specified in the URL (with \'segment\' - with \'auto_detect.enabled\')', function () {
    setup_segment_configurations();
    Config::set('i18n.auto_detect.enabled', true);
    Config::set('i18n.auto_detect.method', 'browser');

    /** @var Request $Request */
    $Request = Request::create('/example');
    // Simulate a request with 'Accept-Language' header set to 'es-es,es;q=0.5'
    $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es-es,es;q=0.5';
    /** @var Response $response */
    $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
        return $request;
    });

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())->toBe('http://localhost/es/example');
});

test('handle(): redirects to default locale if no locale is specified in the URL and client locale is not available (with \'segment\' - with \'auto_detect.enabled\')', function () {
    setup_segment_configurations();
    Config::set('i18n.auto_detect.enabled', true);
    Config::set('i18n.auto_detect.method', 'browser');

    /** @var Request $Request */
    $Request = Request::create('/example');
    // Simulate a request with 'Accept-Language' header set to 'es-es,es;q=0.5'
    $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'xx-xx,xx;q=0.5';
    /** @var Response $response */
    $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
        return $request;
    });

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())->toBe('http://localhost/de/example');
});

test('handle(): throws NotFoundHttpException wrong locale is specified in the URL (with \'segment\' - without \'auto_detect.enabled\')', function () {
    setup_segment_configurations();
    Config::set('i18n.auto_detect.enabled', false);

    try {
        /** @var Request $Request */
        $Request = Request::create('/xx/example');
        /** @var Response $response */
        $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
            return $request;
        });
    }
    catch (NotFoundHttpException $e) {
        expect($e)->toBeInstanceOf(NotFoundHttpException::class);
    }
});

test('handle(): redirects to default locale if no locale is specified in the URL (with \'subdomain\' - without \'auto_detect.enabled\')', function () {
    setup_subdomain_configurations();
    Config::set('i18n.auto_detect.enabled', false);

    /** @var Request $Request */
    $Request = Request::create('/example');
    /** @var Response $response */
    $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
        return $request;
    });

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())->toBe('http://de.localhost/example');
});

test('handle(): redirects to client locale if no locale is specified in the URL (with \'subdomain\' - with \'auto_detect.enabled\')', function () {
    setup_subdomain_configurations();
    Config::set('i18n.auto_detect.enabled', true);
    Config::set('i18n.auto_detect.method', 'browser');

    /** @var Request $Request */
    $Request = Request::create('/example');
    // Simulate a request with 'Accept-Language' header set to 'es-es,es;q=0.5'
    $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'es-es,es;q=0.5';
    /** @var Response $response */
    $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
        return $request;
    });

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())->toBe('http://es.localhost/example');
});

test('handle(): redirects to default locale if no locale is specified in the URL and client locale is not available (with \'subdomain\' - with \'auto_detect.enabled\')', function () {
    setup_subdomain_configurations();
    Config::set('i18n.auto_detect.enabled', true);
    Config::set('i18n.auto_detect.method', 'browser');

    /** @var Request $Request */
    $Request = Request::create('/example');
    // Simulate a request with 'Accept-Language' header set to 'es-es,es;q=0.5'
    $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'xx-xx,xx;q=0.5';
    /** @var Response $response */
    $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
        return $request;
    });

    expect($response)->toBeInstanceOf(RedirectResponse::class);
    expect($response->getTargetUrl())->toBe('http://de.localhost/example');
});

test('handle(): throws NotFoundHttpException wrong locale is specified in the URL (with \'subdomain\' - without \'auto_detect.enabled\')', function () {
    setup_subdomain_configurations();
    Config::set('i18n.auto_detect.enabled', false);

    try {
        /** @var Request $Request */
        $Request = Request::create('http://xx.localhost/example');
        /** @var Response $response */
        $response = (new LocaleMiddleware())->handle($Request, function (Request $request) {
            return $request;
        });
    }
    catch (NotFoundHttpException $e) {
        expect($e)->toBeInstanceOf(NotFoundHttpException::class);
    }
});
