<?php

namespace Database\Factories;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика для генерации компьютеров.
 * CODE = [A-Z]{3}-[0-9]{3}, STR_LEN = 187, MIN_LEN = 4.
 */
class ComputerFactory extends Factory
{
    public function definition(): array
    {
        // Генерируем код формата [A-Z]{3}-[0-9]{3} (CODE_L=3, CODE_D=3)
        $code = strtoupper(fake()->lexify('???')) . '-' . fake()->numerify('###');

        return [
            'code'             => $code,
            'inventory_number' => 'INV-' . fake()->unique()->numerify('#####'),
            'zone_id'          => Zone::factory(),
            'cpu'              => fake()->randomElement([
                'Intel Core i9-14900KF',
                'Intel Core i7-14700K',
                'Intel Core i5-13400F',
                'AMD Ryzen 9 7950X3D',
                'AMD Ryzen 7 7800X3D',
                'AMD Ryzen 5 5600X',
                'Intel Core i9-13900K',
                'AMD Ryzen 9 9950X',
            ]),
            'ram_gb'           => fake()->randomElement([8, 16, 32, 64]),
            'hour_price'       => fake()->randomFloat(2, 100, 500),
            'is_vip'           => fake()->boolean(30),
        ];
    }
}
