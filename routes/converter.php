<?php

use Illuminate\Support\Facades\Route;

// Route::middleware(['web'])->group(function () {
Route::get("convert/{currency}", function ($currency) {
    $amount = \Converter::convert($currency);
    return $amount ?? "Currency not supported";
})->name("convert");
// });
