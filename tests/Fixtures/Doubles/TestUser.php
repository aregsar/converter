<?php

namespace Aregsar\Converter\Tests\Fixtures\Doubles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Aregsar\Converter\Traits\HasManyNotes;
use Aregsar\Converter\Tests\Fixtures\Factories\TestUserFactory;


class TestUser extends Authenticatable
{
    use HasManyNotes, HasFactory;

    protected $guarded = [];

    protected $table = "users";

    protected static function newFactory()
    {
        return TestUserFactory::new();
    }
}
