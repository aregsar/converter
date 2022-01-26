<?php

namespace Aregsar\Converter\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $table = "acmenotes";

    protected $guarded = [];

    protected static function newFactory()
    {
        return new \Aregsar\Converter\Database\Factories\NoteFactory;
        //alternative
        //return \Aregsar\Converter\Database\Factories\NoteFactory::new();
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
