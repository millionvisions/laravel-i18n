<?php

namespace MillionVisions\LaravelI18n\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use function MillionVisions\LaravelI18n\Tests\setup_query_configurations;

test('@alternate(): (with \'query\' - without \'auto_detect.enabled\')', function () {
    setup_query_configurations();

    Config::set('i18n.auto_detect.enabled', false);
    Config::set('i18n.auto_detect.method', 'browser');

    //TODO: Implement test
    expect(true)->toBeTrue();
});
