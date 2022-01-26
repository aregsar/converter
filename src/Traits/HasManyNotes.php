<?php

namespace Aregsar\Converter\Traits;

use Aregsar\Converter\Models\Note;

trait HasManyNotes
{
    public function notes()
    {
        return $this->morphMany(Note::class, "owner");
    }
}
