<?php

namespace Database\Seeders;

use App\Models\Computer;
use App\Models\Zone;
use Illuminate\Database\Seeder;

/**
 * Сидер базы данных.
 * SEED = 41 (12 + 29), PARENTS = 5, ITEMS = 7 (минимум).
 * Генерирует 5 зон и минимум 41 компьютер.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Создаём 5 родительских зон (PARENTS = 5)
        $zones = collect([
            ['name' => 'VIP Lounge',   'floor' => 2, 'description' => 'Элитная VIP-зона с премиальными креслами и 240Hz мониторами'],
            ['name' => 'Общий зал',    'floor' => 1, 'description' => 'Просторный общий зал для повседневного гейминга'],
            ['name' => 'Bootcamp',     'floor' => 2, 'description' => 'Тренировочная зона для киберспортивных команд'],
            ['name' => 'Cyber Arena',  'floor' => 3, 'description' => 'Арена для турниров и киберспортивных соревнований'],
            ['name' => 'Retro Corner', 'floor' => 1, 'description' => 'Ретро-зона с классическими компьютерами и консолями'],
        ])->map(fn ($z) => Zone::create($z));

        // 2. Создаём SEED = 41 компьютер, распределяя по зонам
        //    У каждой зоны будет минимум 2 записи (требование ТЗ)
        $zoneIds = $zones->pluck('id');

        // Сначала по 2 записи на каждую зону (= 10 штук)
        foreach ($zoneIds as $zoneId) {
            Computer::factory()->count(2)->create(['zone_id' => $zoneId]);
        }

        // Оставшиеся 41 - 10 = 31 записей распределяем случайно
        Computer::factory()->count(31)->create([
            'zone_id' => fn () => $zoneIds->random(),
        ]);
    }
}
