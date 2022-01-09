<?php

use Illuminate\Support\Facades\Route;

$routeNamePrefix = config('acme-converter.route_name_prefix');
$routeUrlPrefix = config('acme-converter.route_url_prefix');
$routeMiddlewareGroup = config('acme-converter.route_middleware_group');

Route::middleware([$routeMiddlewareGroup])
    ->prefix($routeUrlPrefix)
    ->name($routeNamePrefix)
    // uncomment namespace is to be able to use classic controller@action style routes
    // ->namespace("Aregsar\Converter\Http\Controllers")
    ->group(function () {
        Route::get("convert/{currency}", function ($currency) {
            $amount = \Converter::convert($currency);

            $amount ??= "Currency not supported";

            return view("acme-converter::converter.convert", [
                "amount" => $amount,
            ]);
        })->name("convert");


        //the controller action passed to the route method as an array also works for pre Laravel 8
        //as well whether or not we set the route namespace (Route::namesapce(Acme\Converter\Http\Controllers))
        //as is done in the application app/Providers/RouteServiceProvider for pre Laravel 8 routes
        Route::get(
            "conversion/convert/{currency}",
            [\Aregsar\Converter\Http\Controllers\ConversionController::class, "convert"]
        )->name("conversion.convert");

        // this pld school pre Laravel 8 route version works if we uncomment the route namespace
        // for the route group
        // Route::get(
        //     "conversion/convert/{currency}",
        //     "ConversionController@convert"
        // )->name("conversion.convert");
    });
