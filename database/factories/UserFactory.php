<?php

namespace Database\Factories;
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory{
    protected $model = User::class;

    public function definition()
    {
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->userName,
            'address' => $this->faker->word,
            'description' => $this->faker->text,
            'password' => bcrypt($this->faker->word),
            'status' => 'ACTIVE',
        ];
    }
}

