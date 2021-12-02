<?php

use Illuminate\Support\Facades\Route;

$routeNamePrefix = config('acme-converter.route_name_prefix');
$routeUrlPrefix = config('acme-converter.route_url_prefix');
$routeMiddlewareGroup = config('acme-converter.route_middleware_group');

Route::middleware([$routeMiddlewareGroup])
    ->prefix($routeUrlPrefix)
    ->name($routeNamePrefix)
    ->group(function () {
        Route::get("convert/{currency}", function ($currency) {
            $amount = \Converter::convert($currency);
            return $amount ?? "Currency not supported";
        })->name("convert");
    });
