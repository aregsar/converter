<?php

namespace Aregsar\Converter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Aregsar\Converter\Models\Note;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition()
    {
        return [
            'content'      => $this->faker->paragraph,
            'owner_id' => -1, //this needs to get overriden by instance id of type
            'owner_type' => "", // this needs to get overridden by the actual type. e.g. \App\User::class
        ];
    }
}
