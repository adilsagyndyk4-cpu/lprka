<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика для генерации зон компьютерного клуба.
 * PARENTS = 5 (3 + 29 mod 3).
 */
class ZoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->randomElement([
                'VIP Lounge', 'Общий зал', 'Bootcamp', 'Cyber Arena', 'Retro Corner',
            ]),
            'floor'       => fake()->numberBetween(1, 3),
            'description' => fake()->sentence(8),
        ];
    }
}
