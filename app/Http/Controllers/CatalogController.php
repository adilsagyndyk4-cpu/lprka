<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComputerRequest;
use App\Models\Computer;
use App\Models\Zone;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Количество записей на одной странице пагинации.
     * PER_PAGE = 3 + (29 mod 4) = 4.
     */
    private const PER_PAGE = 4;

    /**
     * GET /catalog — список компьютеров с пагинацией, поиском и фильтрами.
     * ⭐ Задание повышенного уровня 1: фильтр по родителю (?zone_id=) и логическому полю (?is_vip=).
     */
    public function index(Request $request)
    {
        $query = Computer::with('zone'); // Eager loading связи

        // Поиск по q
        $search = $request->query('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('inventory_number', 'like', "%{$search}%")
                  ->orWhere('cpu', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // ⭐ Фильтр по родителю (zone_id)
        $zoneId = $request->query('zone_id');
        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        // ⭐ Фильтр по логическому полю (is_vip)
        $isVip = $request->query('is_vip');
        if ($isVip !== null && $isVip !== '') {
            $query->where('is_vip', (bool) $isVip);
        }

        // Сортировка: N нечётный (29) → по основному строковому полю А→Я (inventory_number)
        $sort = $request->query('sort', 'inventory_number');
        if (in_array($sort, ['inventory_number', 'ram_gb', 'hour_price'])) {
            $query->orderBy($sort, 'asc');
        } else {
            $query->orderBy('inventory_number', 'asc');
        }

        $computers = $query->paginate(self::PER_PAGE)->withQueryString();
        $zones = Zone::orderBy('name')->get();

        return view('catalog.index', [
            'title'     => 'Каталог компьютеров',
            'computers' => $computers,
            'zones'     => $zones,
            'search'    => $search,
            'sort'      => $sort,
            'zoneId'    => $zoneId,
            'isVip'     => $isVip,
        ]);
    }

    /**
     * GET /catalog/create — форма создания нового компьютера.
     */
    public function create()
    {
        $zones = Zone::orderBy('name')->get();
        $games = \App\Models\Game::orderBy('title')->get();

        return view('catalog.create', [
            'title'    => 'Добавить компьютер',
            'computer' => new Computer(),
            'zones'    => $zones,
            'games'    => $games,
        ]);
    }

    /**
     * POST /catalog — сохранение нового компьютера в БД с валидацией.
     */
    public function store(ComputerRequest $request)
    {
        $computer = Computer::create($request->validated());
        if ($request->has('games')) {
            $computer->games()->sync($request->input('games'));
        }

        return redirect()->route('catalog.index')
            ->with('status', "Добавлено: {$computer->inventory_number} (id {$computer->id})");
    }

    /**
     * GET /catalog/{computer}/edit — форма редактирования компьютера.
     */
    public function edit(Computer $computer)
    {
        $computer->load('games');
        $zones = Zone::orderBy('name')->get();
        $games = \App\Models\Game::orderBy('title')->get();

        return view('catalog.edit', [
            'title'    => "Редактирование: {$computer->inventory_number}",
            'computer' => $computer,
            'zones'    => $zones,
            'games'    => $games,
        ]);
    }

    /**
     * PUT /catalog/{computer} — обновление компьютера в БД с валидацией.
     */
    public function update(ComputerRequest $request, Computer $computer)
    {
        $computer->update($request->validated());
        $computer->games()->sync($request->input('games', []));

        return redirect()->route('catalog.index')
            ->with('status', "Изменения сохранены для {$computer->inventory_number}.");
    }


    /**
     * DELETE /catalog/{computer} — удаление компьютера из БД.
     */
    public function destroy(Computer $computer)
    {
        $inv = $computer->inventory_number;
        $computer->delete();

        return redirect()->route('catalog.index')
            ->with('status', "Удалено: {$inv}");
    }

    /**
     * ⭐ GET /catalog/stats — агрегированная статистика по коллекции / БД.
     */
    public function stats()
    {
        $count    = Computer::count();
        $avgPrice = round(Computer::avg('hour_price'), 2);
        $minPrice = Computer::min('hour_price');
        $maxPrice = Computer::max('hour_price');
        $avgRam   = round(Computer::avg('ram_gb'), 1);

        return view('catalog.stats', [
            'title'    => 'Статистика каталога компьютеров',
            'count'    => $count,
            'avgPrice' => $avgPrice,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'avgRam'   => $avgRam,
        ]);
    }

    /**
     * ⭐ GET /catalog/zone/{zone?} — список зон клуба или компьютеры зоны.
     */
    public function byZone(string|Zone|null $zone = null)
    {
        if (! $zone) {
            $zones = Zone::withCount('computers')->orderBy('name')->get();

            return view('catalog.zones', [
                'title' => 'Все зоны клуба',
                'zones' => $zones,
            ]);
        }

        $zoneModel = is_string($zone)
            ? (is_numeric($zone) ? Zone::find($zone) : Zone::where('name', $zone)->first())
            : $zone;

        abort_if(! $zoneModel, 404);

        $computers = Computer::with('zone')
            ->where('zone_id', $zoneModel->id)
            ->orderBy('inventory_number', 'asc')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $zones = Zone::orderBy('name')->get();

        return view('catalog.index', [
            'title'     => "Компьютеры зоны: {$zoneModel->name}",
            'computers' => $computers,
            'zones'     => $zones,
            'search'    => null,
            'sort'      => 'inventory_number',
            'zoneId'    => $zoneModel->id,
            'isVip'     => null,
        ]);
    }

    /**
     * ⭐ DELETE /catalog/zone/{zone} — каскадное удаление зоны с предупреждением.
     */
    public function destroyZone(Zone $zone)
    {
        $count = $zone->computers()->count();
        $name = $zone->name;
        $zone->delete(); // Благодаря cascadeOnDelete удалятся и все компьютеры

        return redirect()->route('catalog.zone')
            ->with('status', "Зона «{$name}» и связанные с ней компьютеры ({$count} шт.) удалены по правилу CASCADE!");
    }

    /**
     * GET /catalog/code/{code} — просмотр по коду.
     */
    public function byCode(string $code)
    {
        $computer = Computer::with(['zone', 'games'])->where('code', $code)->firstOrFail();

        return view('catalog.show', [
            'title'    => "Компьютер {$computer->inventory_number} ({$computer->code})",
            'computer' => $computer,
        ]);
    }

    /**
     * GET /catalog/{computer} — детальная карточка компьютера.
     */
    public function show(Computer $computer)
    {
        $computer->load(['zone', 'games']);

        return view('catalog.show', [
            'title'    => "Компьютер {$computer->inventory_number}",
            'computer' => $computer,
        ]);
    }
}
