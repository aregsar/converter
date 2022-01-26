<?php

namespace Aregsar\Converter\Tests\Fixtures\Factories;

use Orchestra\Testbench\Factories\UserFactory as OrchestraUserFactory;
use Aregsar\Converter\Tests\Fixtures\Doubles\TestUser;

class TestUserFactory extends OrchestraUserFactory
{
    protected $model = TestUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name,
            "email" => $this->faker->unique()->safeEmail,
            "email_verified_at" => now(),
            "password" => bcrypt("password"),
            "remember_token" => "abcd",
        ];
    }
}
