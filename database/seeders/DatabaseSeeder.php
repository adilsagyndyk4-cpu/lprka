<?php

namespace Database\Seeders;

use App\Models\Computer;
use App\Models\Zone;
use Illuminate\Database\Seeder;

/**
 * Сидер базы данных.
 * SEED = 41 (12 + 29), PARENTS = 5, ITEMS = 7 (минимум).
 * Генерирует 5 зон и ровно 41 компьютер.
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

        $vipZone = $zones[0];
        $commonZone = $zones[1];
        $bootcampZone = $zones[2];
        $arenaZone = $zones[3];
        $retroZone = $zones[4];

        // Первые компьютеры создаем с фиксированными реалистичными данными
        Computer::create([
            'zone_id' => $vipZone->id,
            'code' => 'VIP-101',
            'inventory_number' => 'INV-00101',
            'description' => 'Флагманский ПК киберспортивного клуба с водяным охлаждением',
            'cpu' => 'Intel Core i9-14900KF',
            'ram_gb' => 64,
            'hour_price' => 350.00,
            'is_vip' => true,
        ]);

        Computer::create([
            'zone_id' => $vipZone->id,
            'code' => 'VIP-102',
            'inventory_number' => 'INV-00102',
            'description' => 'Премиум ПК зоны VIP Lounge',
            'cpu' => 'Intel Core i9-14900KF',
            'ram_gb' => 64,
            'hour_price' => 350.00,
            'is_vip' => true,
        ]);

        Computer::create([
            'zone_id' => $commonZone->id,
            'code' => 'STD-201',
            'inventory_number' => 'INV-00201',
            'description' => 'Рабочая игровая станция общего зала',
            'cpu' => 'Intel Core i5-13400F',
            'ram_gb' => 16,
            'hour_price' => 150.00,
            'is_vip' => false,
        ]);

        Computer::create([
            'zone_id' => $commonZone->id,
            'code' => 'STD-202',
            'inventory_number' => 'INV-00202',
            'description' => 'Игровая станция общего зала',
            'cpu' => 'AMD Ryzen 5 5600X',
            'ram_gb' => 16,
            'hour_price' => 140.00,
            'is_vip' => false,
        ]);

        Computer::create([
            'zone_id' => $bootcampZone->id,
            'code' => 'BTC-301',
            'inventory_number' => 'INV-00301',
            'description' => 'Командный компьютер для буткемпа',
            'cpu' => 'Intel Core i7-14700K',
            'ram_gb' => 32,
            'hour_price' => 250.00,
            'is_vip' => false,
        ]);

        Computer::create([
            'zone_id' => $bootcampZone->id,
            'code' => 'BTC-302',
            'inventory_number' => 'INV-00302',
            'description' => 'Командный компьютер для буткемпа №2',
            'cpu' => 'Intel Core i7-14700K',
            'ram_gb' => 32,
            'hour_price' => 250.00,
            'is_vip' => false,
        ]);

        Computer::create([
            'zone_id' => $arenaZone->id,
            'code' => 'ARN-401',
            'inventory_number' => 'INV-00401',
            'description' => 'Турнирный ПК Cyber Arena',
            'cpu' => 'AMD Ryzen 7 7800X3D',
            'ram_gb' => 32,
            'hour_price' => 300.00,
            'is_vip' => true,
        ]);

        Computer::create([
            'zone_id' => $retroZone->id,
            'code' => 'RTR-501',
            'inventory_number' => 'INV-00501',
            'description' => 'Ретро игровой компьютер',
            'cpu' => 'Intel Core 2 Quad Q6600',
            'ram_gb' => 8,
            'hour_price' => 100.00,
            'is_vip' => false,
        ]);

        Computer::create([
            'zone_id' => $retroZone->id,
            'code' => 'RTR-502',
            'inventory_number' => 'INV-00502',
            'description' => 'Ретро игровой компьютер №2',
            'cpu' => 'Intel Core i5-2500K',
            'ram_gb' => 8,
            'hour_price' => 110.00,
            'is_vip' => false,
        ]);

        // Создано 9 записей, у каждой из 5 зон есть минимум 2 записи (кроме arena, добавим еще 1 для arena)
        Computer::create([
            'zone_id' => $arenaZone->id,
            'code' => 'ARN-402',
            'inventory_number' => 'INV-00402',
            'description' => 'Турнирный ПК Cyber Arena №2',
            'cpu' => 'AMD Ryzen 7 7800X3D',
            'ram_gb' => 32,
            'hour_price' => 300.00,
            'is_vip' => true,
        ]);

        // Создано 10 записей (по 2 на каждую зону).
        // Добавляем еще 31 запись через фабрику, чтобы общее число было ровно SEED = 41!
        $allZoneIds = $zones->pluck('id');
        Computer::factory()->count(31)->create([
            'zone_id' => fn () => $allZoneIds->random(),
        ]);

        // 3. Создаем игры (⭐ M:N сущность Game)
        $games = collect([
            ['title' => 'Counter-Strike 2', 'genre' => 'Tactical Shooter'],
            ['title' => 'Dota 2', 'genre' => 'MOBA'],
            ['title' => 'Cyberpunk 2077', 'genre' => 'Action RPG'],
            ['title' => 'Grand Theft Auto V', 'genre' => 'Action-Adventure'],
            ['title' => 'Valorant', 'genre' => 'Tactical Shooter'],
            ['title' => 'Apex Legends', 'genre' => 'Battle Royale'],
        ])->map(fn ($g) => \App\Models\Game::create($g));

        $gameIds = $games->pluck('id');
        foreach (Computer::all() as $comp) {
            $comp->games()->sync($gameIds->random(rand(2, 5)));
        }
    }

}
