<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Временное статическое хранилище компьютеров (ЛПР № 8)
     */
    private function computers(): array
    {
        return [
            [
                'id' => 1,
                'code' => 'VIP-101',
                'inventory_number' => 'INV-00101',
                'zone' => 'VIP Lounge',
                'floor' => 2,
                'cpu' => 'Intel Core i9-14900KF',
                'ram_gb' => 64,
                'hour_price' => 350.00,
                'is_vip' => true,
            ],
            [
                'id' => 2,
                'code' => 'VIP-102',
                'inventory_number' => 'INV-00102',
                'zone' => 'VIP Lounge',
                'floor' => 2,
                'cpu' => 'Intel Core i9-14900KF',
                'ram_gb' => 64,
                'hour_price' => 350.00,
                'is_vip' => true,
            ],
            [
                'id' => 3,
                'code' => 'VIP-103',
                'inventory_number' => 'INV-00103',
                'zone' => 'VIP Lounge',
                'floor' => 2,
                'cpu' => 'AMD Ryzen 9 7950X3D',
                'ram_gb' => 64,
                'hour_price' => 380.00,
                'is_vip' => true,
            ],
            [
                'id' => 4,
                'code' => 'STD-201',
                'inventory_number' => 'INV-00201',
                'zone' => 'Общий зал',
                'floor' => 1,
                'cpu' => 'Intel Core i5-13400F',
                'ram_gb' => 16,
                'hour_price' => 150.00,
                'is_vip' => false,
            ],
            [
                'id' => 5,
                'code' => 'STD-202',
                'inventory_number' => 'INV-00202',
                'zone' => 'Общий зал',
                'floor' => 1,
                'cpu' => 'AMD Ryzen 5 5600X',
                'ram_gb' => 16,
                'hour_price' => 140.00,
                'is_vip' => false,
            ],
            [
                'id' => 6,
                'code' => 'BTC-301',
                'inventory_number' => 'INV-00301',
                'zone' => 'Bootcamp',
                'floor' => 2,
                'cpu' => 'Intel Core i7-14700K',
                'ram_gb' => 32,
                'hour_price' => 250.00,
                'is_vip' => false,
            ],
            [
                'id' => 7,
                'code' => 'BTC-302',
                'inventory_number' => 'INV-00302',
                'zone' => 'Bootcamp',
                'floor' => 2,
                'cpu' => 'Intel Core i7-14700K',
                'ram_gb' => 32,
                'hour_price' => 250.00,
                'is_vip' => false,
            ],
        ]; // ровно ITEMS = 7 записей
    }

    // GET /catalog?q=...&sort=...
    public function index(Request $request)
    {
        $computers = collect($this->computers());
        $search = $request->query('q');

        if ($search) {
            $computers = $computers->filter(function ($item) use ($search) {
                return mb_stripos($item['inventory_number'], $search) !== false
                    || mb_stripos($item['cpu'], $search) !== false
                    || mb_stripos($item['code'], $search) !== false;
            });
        }

        $sort = $request->query('sort', 'inventory_number');
        if (in_array($sort, ['inventory_number', 'ram_gb', 'hour_price'])) {
            $computers = $computers->sortBy($sort);
        }

        return view('catalog.index', [
            'title' => 'Каталог компьютеров',
            'computers' => $computers,
            'search' => $search,
            'sort' => $sort,
        ]);
    }

    // ⭐ GET /catalog/stats (повышенный уровень 1)
    public function stats()
    {
        $computers = collect($this->computers());
        $count = $computers->count();
        $avgPrice = round($computers->avg('hour_price'), 2);
        $minPrice = $computers->min('hour_price');
        $maxPrice = $computers->max('hour_price');
        $avgRam = round($computers->avg('ram_gb'), 1);

        return view('catalog.stats', [
            'title' => 'Статистика каталога компьютеров',
            'count' => $count,
            'avgPrice' => $avgPrice,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'avgRam' => $avgRam,
        ]);
    }

    // GET /catalog/zone/{zone?}
    // ⭐ повышенный уровень 2: без параметра выводит список зон
    public function byZone(?string $zone = null)
    {
        $all = collect($this->computers());

        if (! $zone) {
            $zones = $all->map(function ($item) {
                return [
                    'name' => $item['zone'],
                    'floor' => $item['floor'],
                ];
            })->unique('name')->values();

            return view('catalog.zones', [
                'title' => 'Все зоны клуба',
                'zones' => $zones,
                'allCount' => $all->count(),
            ]);
        }

        $computers = $all->where('zone', $zone);

        return view('catalog.index', [
            'title' => "Компьютеры зоны: {$zone}",
            'computers' => $computers,
            'search' => null,
            'sort' => 'inventory_number',
        ]);
    }

    // GET /catalog/code/{code}
    public function byCode(string $code)
    {
        $computer = collect($this->computers())->firstWhere('code', $code);
        abort_if(! $computer, 404);

        return view('catalog.show', [
            'title' => "Компьютер {$computer['inventory_number']} ({$computer['code']})",
            'computer' => $computer,
        ]);
    }

    // GET /catalog/{id}
    public function show(int $id)
    {
        $computer = collect($this->computers())->firstWhere('id', $id);
        abort_if(! $computer, 404);

        return view('catalog.show', [
            'title' => "Компьютер {$computer['inventory_number']}",
            'computer' => $computer,
        ]);
    }
}
