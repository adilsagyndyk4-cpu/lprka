<?php

namespace Tests\Feature;

use Tests\TestCase;

class Lab8Test extends TestCase
{
    public function test_root_redirects_to_catalog()
    {
        $response = $this->get('/');
        $response->assertRedirect(route('catalog.index'));
    }

    public function test_catalog_index_displays_items_and_footer()
    {
        $response = $this->get('/catalog');
        $response->assertStatus(200);
        $response->assertSee('Каталог компьютеров');
        $response->assertSee('INV-00101');
        $response->assertSee('VIP-101');
        $response->assertSee('VIP Lounge');
        $response->assertSee('K7-ROSA');
    }

    public function test_catalog_search()
    {
        $response = $this->get('/catalog?q=INV-00201');
        $response->assertStatus(200);
        $response->assertSee('INV-00201');
        $response->assertDontSee('INV-00101');
    }

    public function test_catalog_sort()
    {
        $response = $this->get('/catalog?sort=hour_price');
        $response->assertStatus(200);
        $response->assertSee('INV-00501');
    }


    public function test_catalog_show_by_id()
    {
        $response = $this->get('/catalog/1');
        $response->assertStatus(200);
        $response->assertSee('INV-00101');
        $response->assertSee('Intel Core i9-14900KF');
        $response->assertSee('64 ГБ');

        $response404 = $this->get('/catalog/999');
        $response404->assertStatus(404);
    }

    public function test_catalog_show_by_code()
    {
        $response = $this->get('/catalog/code/VIP-101');
        $response->assertStatus(200);
        $response->assertSee('VIP-101');

        $response404 = $this->get('/catalog/code/INVALID-CODE');
        $response404->assertStatus(404);
    }

    public function test_catalog_zones_and_filter()
    {
        $response = $this->get('/catalog/zone');
        $response->assertStatus(200);
        $response->assertSee('Все зоны клуба');
        $response->assertSee('VIP Lounge');
        $response->assertSee('Bootcamp');

        $filtered = $this->get('/catalog/zone/Bootcamp');
        $filtered->assertStatus(200);
        $filtered->assertSee('INV-00301');
        $filtered->assertDontSee('INV-00101');
    }

    public function test_catalog_stats()
    {
        $response = $this->get('/catalog/stats');
        $response->assertStatus(200);
        $response->assertSee('Статистика каталога компьютеров');
        $response->assertSee('Всего компьютеров (count)');
        $response->assertSee((string) \App\Models\Computer::count());
    }

    public function test_feedback_form_and_submission()
    {
        $response = $this->get('/feedback');
        $response->assertStatus(200);
        $response->assertSee('Обратная связь');

        $postResponse = $this->post('/feedback', [
            'name' => 'Адиль',
            'message' => 'Отличные ПК!',
        ]);

        $postResponse->assertRedirect(route('feedback.form'));
        $postResponse->assertSessionHas('status', 'Спасибо, Адиль! Сообщение получено: Отличные ПК!');
    }

    public function test_hello_routes()
    {
        $this->get('/hello')->assertSee('Привет, гость!');
        $this->get('/hello/Adil')->assertSee('Привет, Adil!');
        $this->get('/hello/Адиль')->assertSee('Привет, Адиль!');
    }

    public function test_custom_404_page()
    {
        $response = $this->get('/unknown-route-12345');
        $response->assertStatus(404);
        $response->assertSee('unknown-route-12345');
    }
}
