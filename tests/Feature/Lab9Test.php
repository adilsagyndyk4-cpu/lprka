<?php

namespace Tests\Feature;

use App\Models\Computer;
use App\Models\Zone;
use Tests\TestCase;

class Lab9Test extends TestCase
{
    /**
     * Часть 4: Пагинация PER_PAGE = 4
     */
    public function test_pagination_per_page_is_4()
    {
        $response = $this->get('/catalog');
        $response->assertStatus(200);

        // Проверяем, что пагинатор передает ровно 4 записи на страницу (PER_PAGE = 4)
        $computers = $response->viewData('computers');
        $this->assertEquals(4, $computers->perPage());
    }

    /**
     * ⭐ Задание повышенного уровня 1: Фильтр по родителю (zone_id)
     */
    public function test_filter_by_parent_zone()
    {
        $zone = Zone::first();
        $response = $this->get("/catalog?zone_id={$zone->id}");
        $response->assertStatus(200);

        $computers = $response->viewData('computers');
        foreach ($computers as $comp) {
            $this->assertEquals($zone->id, $comp->zone_id);
        }
    }

    /**
     * ⭐ Задание повышенного уровня 1: Фильтр по логическому полю (is_vip)
     */
    public function test_filter_by_is_vip()
    {
        $responseVip = $this->get('/catalog?is_vip=1');
        $responseVip->assertStatus(200);
        $computersVip = $responseVip->viewData('computers');
        foreach ($computersVip as $comp) {
            $this->assertTrue((bool)$comp->is_vip);
        }

        $responseRegular = $this->get('/catalog?is_vip=0');
        $responseRegular->assertStatus(200);
        $computersRegular = $responseRegular->viewData('computers');
        foreach ($computersRegular as $comp) {
            $this->assertFalse((bool)$comp->is_vip);
        }
    }

    /**
     * Часть 5: Успешное создание компьютера с валидацией ComputerRequest
     */
    public function test_computer_store_success()
    {
        $zone = Zone::first();
        $randSuffix = rand(100, 999);
        $code = 'T' . chr(rand(65, 90)) . chr(rand(65, 90)) . '-' . $randSuffix;
        $inv = 'INV-TST-' . uniqid();

        $data = [
            'code' => $code,
            'inventory_number' => $inv,
            'zone_id' => $zone->id,
            'description' => 'Тестовое описание игрового компьютера',
            'cpu' => 'Intel Core i9-14900KF',
            'ram_gb' => 32,
            'hour_price' => 280.00,
            'is_vip' => '1',
        ];

        $response = $this->post('/catalog', $data);
        $response->assertRedirect(route('catalog.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('computers', [
            'code' => $code,
            'inventory_number' => $inv,
        ]);
    }

    /**
     * Часть 5: Ошибки валидации (MIN_LEN = 4, exists, unique, regex)
     */
    public function test_computer_validation_fails_on_invalid_data()
    {
        // Некорректные данные: короткий инвентарный номер (меньше MIN_LEN=4),
        // неверный формат кода, несуществующая зона
        $response = $this->post('/catalog', [
            'code' => 'invalid-code',
            'inventory_number' => 'A', // меньше MIN_LEN = 4
            'zone_id' => 99999,        // несуществующий ID родителя
            'cpu' => 'cpu',
            'ram_gb' => 0,
            'hour_price' => -10,
        ]);

        $response->assertSessionHasErrors(['code', 'inventory_number', 'zone_id', 'ram_gb', 'hour_price']);
    }

    /**
     * Часть 5: Обновление записи (Update)
     */
    public function test_computer_update_success()
    {
        $testComp = Computer::create([
            'zone_id' => Zone::first()->id,
            'code' => 'U' . chr(rand(65, 90)) . chr(rand(65, 90)) . '-' . rand(100, 999),
            'inventory_number' => 'INV-UP-' . uniqid(),
            'cpu' => 'Old CPU',
            'ram_gb' => 16,
            'hour_price' => 200,
            'is_vip' => false,
        ]);

        $newCpu = 'AMD Ryzen 9 9950X Extreme Edition';

        $response = $this->put("/catalog/{$testComp->id}", [
            'code' => $testComp->code,
            'inventory_number' => $testComp->inventory_number,
            'zone_id' => $testComp->zone_id,
            'cpu' => $newCpu,
            'ram_gb' => 64,
            'hour_price' => 450.00,
            'is_vip' => '1',
        ]);

        $response->assertRedirect(route('catalog.index'));
        $this->assertDatabaseHas('computers', [
            'id' => $testComp->id,
            'cpu' => $newCpu,
        ]);
    }

    /**
     * Часть 6: Удаление записи (Destroy)
     */
    public function test_computer_destroy_success()
    {
        $computer = Computer::create([
            'zone_id' => Zone::first()->id,
            'code' => 'D' . chr(rand(65, 90)) . chr(rand(65, 90)) . '-' . rand(100, 999),
            'inventory_number' => 'INV-DEL-' . uniqid(),
            'cpu' => 'Intel Core i5',
            'ram_gb' => 16,
            'hour_price' => 150.00,
            'is_vip' => false,
        ]);

        $response = $this->delete("/catalog/{$computer->id}");
        $response->assertRedirect(route('catalog.index'));

        $this->assertDatabaseMissing('computers', [
            'id' => $computer->id,
        ]);
    }

    /**
     * ⭐ Задание повышенного уровня 2: Каскадное удаление родителя (ON DELETE CASCADE)
     */
    public function test_zone_cascade_delete_removes_children()
    {
        $tempZone = Zone::create([
            'name' => 'Временная зона для теста каскада ' . uniqid(),
            'floor' => 1,
        ]);

        $c1 = Computer::create([
            'zone_id' => $tempZone->id,
            'code' => 'C' . chr(rand(65, 90)) . chr(rand(65, 90)) . '-' . rand(100, 999),
            'inventory_number' => 'INV-CAS-' . uniqid(),
            'cpu' => 'CPU 1',
            'ram_gb' => 16,
            'hour_price' => 100,
            'is_vip' => false,
        ]);

        $c2 = Computer::create([
            'zone_id' => $tempZone->id,
            'code' => 'C' . chr(rand(65, 90)) . chr(rand(65, 90)) . '-' . rand(100, 999),
            'inventory_number' => 'INV-CAS-' . uniqid(),
            'cpu' => 'CPU 2',
            'ram_gb' => 16,
            'hour_price' => 100,
            'is_vip' => false,
        ]);

        $this->assertDatabaseHas('computers', ['id' => $c1->id]);
        $this->assertDatabaseHas('computers', ['id' => $c2->id]);

        // Удаляем зону через метод destroyZone
        $response = $this->delete("/catalog/zone/{$tempZone->id}");
        $response->assertRedirect(route('catalog.zone'));

        // Зона удалена
        $this->assertDatabaseMissing('zones', ['id' => $tempZone->id]);
        // Компьютеры каскадно удалены базой данных!
        $this->assertDatabaseMissing('computers', ['id' => $c1->id]);
        $this->assertDatabaseMissing('computers', ['id' => $c2->id]);
    }

    /**
     * ⭐ Задание повышенного уровня 3: Связь «многие-ко-многим» с Game (belongsToMany, sync)
     */
    public function test_computer_games_many_to_many_sync()
    {
        $comp = Computer::first();
        $games = \App\Models\Game::take(3)->pluck('id')->toArray();

        $response = $this->put("/catalog/{$comp->id}", [
            'code' => $comp->code,
            'inventory_number' => $comp->inventory_number,
            'zone_id' => $comp->zone_id,
            'cpu' => $comp->cpu,
            'ram_gb' => $comp->ram_gb,
            'hour_price' => $comp->hour_price,
            'is_vip' => $comp->is_vip,
            'games' => $games,
        ]);

        $response->assertRedirect(route('catalog.index'));
        $this->assertEqualsCanonicalizing($games, $comp->fresh()->games->pluck('id')->toArray());
    }
}

