<?php

namespace Aregsar\Converter\Http\Controllers;

class ConversionController extends Controller
{
    public function convert($currency)
    {
        $amount = \Converter::convert($currency);

        $amount ??= "Currency not supported";

        return view("acme-converter::converter.convert", [
            "amount" => $amount,
        ]);
    }
}
