<?php

namespace App\Http\Controllers;

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
     * GET /catalog — список компьютеров с поиском, сортировкой и пагинацией.
     */
    public function index(Request $request)
    {
        $query = Computer::with('zone');
        $search = $request->query('q');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('inventory_number', 'like', "%{$search}%")
                  ->orWhere('cpu', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Сортировка (N нечётный: по умолчанию по inventory_number А->Я)
        $sort = $request->query('sort', 'inventory_number');
        $direction = $request->query('dir', 'asc');

        if (in_array($sort, ['inventory_number', 'ram_gb', 'hour_price'])) {
            $query->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
        }

        $computers = $query->paginate(self::PER_PAGE)->withQueryString();

        return view('catalog.index', [
            'title'     => 'Каталог компьютеров',
            'computers' => $computers,
            'search'    => $search,
            'sort'      => $sort,
        ]);
    }

    /**
     * GET /catalog/create — форма создания нового компьютера.
     */
    public function create()
    {
        $zones = Zone::orderBy('name')->get();

        return view('catalog.create', [
            'title' => 'Добавить компьютер',
            'zones' => $zones,
        ]);
    }

    /**
     * POST /catalog — сохранение нового компьютера в БД.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'             => ['required', 'string', 'regex:/^[A-Z]{3}-[0-9]{3}$/', 'unique:computers,code'],
            'inventory_number' => ['required', 'string', 'min:4', 'max:20', 'unique:computers,inventory_number'],
            'zone_id'          => ['required', 'exists:zones,id'],
            'cpu'              => ['required', 'string', 'min:4', 'max:187'],
            'ram_gb'           => ['required', 'integer', 'min:1', 'max:512'],
            'hour_price'       => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'is_vip'           => ['sometimes', 'boolean'],
        ]);

        $validated['is_vip'] = $request->boolean('is_vip');
        Computer::create($validated);

        return redirect()->route('catalog.index')
            ->with('status', "Компьютер {$validated['inventory_number']} успешно добавлен!");
    }

    /**
     * GET /catalog/{id}/edit — форма редактирования компьютера.
     */
    public function edit(Computer $computer)
    {
        $zones = Zone::orderBy('name')->get();

        return view('catalog.edit', [
            'title'    => "Редактировать: {$computer->inventory_number}",
            'computer' => $computer,
            'zones'    => $zones,
        ]);
    }

    /**
     * PUT /catalog/{id} — обновление компьютера в БД.
     */
    public function update(Request $request, Computer $computer)
    {
        $validated = $request->validate([
            'code'             => ['required', 'string', 'regex:/^[A-Z]{3}-[0-9]{3}$/', "unique:computers,code,{$computer->id}"],
            'inventory_number' => ['required', 'string', 'min:4', 'max:20', "unique:computers,inventory_number,{$computer->id}"],
            'zone_id'          => ['required', 'exists:zones,id'],
            'cpu'              => ['required', 'string', 'min:4', 'max:187'],
            'ram_gb'           => ['required', 'integer', 'min:1', 'max:512'],
            'hour_price'       => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'is_vip'           => ['sometimes', 'boolean'],
        ]);

        $validated['is_vip'] = $request->boolean('is_vip');
        $computer->update($validated);

        return redirect()->route('catalog.show', $computer)
            ->with('status', "Компьютер {$computer->inventory_number} обновлен!");
    }

    /**
     * DELETE /catalog/{id} — удаление компьютера из БД.
     */
    public function destroy(Computer $computer)
    {
        $inv = $computer->inventory_number;
        $computer->delete();

        return redirect()->route('catalog.index')
            ->with('status', "Компьютер {$inv} удален!");
    }

    /**
     * ⭐ GET /catalog/stats — агрегированная статистика.
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
     * ⭐ GET /catalog/zone/{zone?} — фильтрация по зонам.
     */
    public function byZone(?Zone $zone = null)
    {
        if (! $zone) {
            $zones = Zone::withCount('computers')->orderBy('name')->get();

            return view('catalog.zones', [
                'title' => 'Все зоны клуба',
                'zones' => $zones,
            ]);
        }

        $computers = Computer::where('zone_id', $zone->id)
            ->orderBy('inventory_number')
            ->paginate(self::PER_PAGE);

        return view('catalog.index', [
            'title'     => "Компьютеры зоны: {$zone->name}",
            'computers' => $computers,
            'search'    => null,
            'sort'      => 'inventory_number',
        ]);
    }

    /**
     * GET /catalog/code/{code} — поиск по коду.
     */
    public function byCode(string $code)
    {
        $computer = Computer::with('zone')->where('code', $code)->firstOrFail();

        return view('catalog.show', [
            'title'    => "Компьютер {$computer->inventory_number} ({$computer->code})",
            'computer' => $computer,
        ]);
    }

    /**
     * GET /catalog/{computer} — карточка компьютера.
     */
    public function show(Computer $computer)
    {
        $computer->load('zone');

        return view('catalog.show', [
            'title'    => "Компьютер {$computer->inventory_number}",
            'computer' => $computer,
        ]);
    }
}
