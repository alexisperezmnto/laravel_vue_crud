<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->words(3, true),
            'imagen' => $this->faker->imageUrl(),
            'observaciones' => $this->faker->sentence(12)
        ];
    }
}
