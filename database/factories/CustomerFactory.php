<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'imagen' => $this->faker->imageUrl(),
            'cedula' => $this->faker->numberBetween(10000000, 200000000),
            'email' => $this->faker->email(),
            'telefono' => $this->faker->phoneNumber(),
            'observaciones' => $this->faker->sentence(12)
        ];
    }
}
