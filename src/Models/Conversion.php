<?php

namespace Aregsar\Converter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversion extends Model
{
    use HasFactory;

    protected $table = "acmeconversions";

    protected $guarded = [];

    // protected $casts = [
    //     'amount' => 'integer'
    // ];

    protected static function newFactory()
    {
        //return new \Aregsar\Converter\Database\Factories\ConversionFactory;
        //alternative
        return \Aregsar\Converter\Database\Factories\ConversionFactory::new();
    }
}
