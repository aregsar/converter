<?php

namespace Aregsar\Converter\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = "acmenotes";

    protected $guarded = [];

    public function owner()
    {
        return $this->morphTo();
    }
}
