const fs = require('fs');
const path = require('path');
const {
    Document,
    Packer,
    Paragraph,
    TextRun,
    Table,
    TableRow,
    TableCell,
    HeadingLevel,
    BorderStyle,
    WidthType,
    AlignmentType,
    ShadingType
} = require('docx');

function createCell(text, isHeader = false, widthPercent = null) {
    return new TableCell({
        width: widthPercent ? { size: widthPercent, type: WidthType.PERCENTAGE } : undefined,
        shading: isHeader ? { fill: "1E293B", type: ShadingType.CLEAR } : undefined,
        children: [
            new Paragraph({
                alignment: isHeader ? AlignmentType.CENTER : AlignmentType.LEFT,
                children: [
                    new TextRun({
                        text: text,
                        bold: isHeader,
                        color: isHeader ? "FFFFFF" : "000000",
                        size: 20, // 10pt
                        font: "Calibri"
                    })
                ]
            })
        ]
    });
}

function createHeading(text, level = HeadingLevel.HEADING_1) {
    return new Paragraph({
        text: text,
        heading: level,
        spacing: { before: 240, after: 120 }
    });
}

function createParagraph(text, boldPrefix = "") {
    const children = [];
    if (boldPrefix) {
        children.push(new TextRun({ text: boldPrefix + " ", bold: true, size: 22, font: "Calibri" }));
    }
    children.push(new TextRun({ text: text, size: 22, font: "Calibri" }));
    return new Paragraph({
        children: children,
        spacing: { before: 60, after: 60 }
    });
}

function createCodeBlock(codeText) {
    const lines = codeText.split('\n');
    return lines.map(line => new Paragraph({
        children: [
            new TextRun({
                text: line,
                font: "Consolas",
                size: 18,
                color: "1E293B"
            })
        ],
        spacing: { before: 20, after: 20 }
    }));
}

// -------------------------------------------------------------
// ГЕНЕРАЦИЯ ОТЧЕТА ПО ЛПР № 8
// -------------------------------------------------------------
async function generateLab8Docx() {
    const gitLog = fs.readFileSync(path.join(__dirname, '..', 'git-log-lpr8.txt'), 'utf8');

    const paramRows = [
        ['Параметр', 'Значение', 'Описание'],
        ['V', '29', 'Номер варианта (Компьютерный клуб)'],
        ['SLUG', 'adils', 'Фамилия латиницей (до 12 символов)'],
        ['PORT', '8129', 'Порт веб-сервера (8100 + 29)'],
        ['ITEMS', '7', 'Количество записей в массиве (5 + (29 mod 3))'],
        ['CODE_L', '3', 'Количество букв в коде (2 + (29 mod 2))'],
        ['CODE_D', '3', 'Количество цифр в коде (2 + (29 mod 4))'],
        ['DB', 'lpr_adils', 'Имя базы данных'],
        ['PER_PAGE', '4', 'Записей на страницу при пагинации (3 + (29 mod 4))'],
        ['MIN_LEN', '4', 'Минимальная длина основного поля (2 + (29 mod 3))'],
        ['STR_LEN', '187', 'Длина основного поля (100 + 3 * 29)'],
        ['SEED', '41', 'Количество основных записей (12 + 29)'],
        ['PARENTS', '5', 'Количество родительских записей (3 + (29 mod 3))'],
        ['ON_DELETE', 'cascade', 'Поведение внешнего ключа (N=29 нечетный -> cascade)'],
        ['SORT', 'по основному полю А->Я', 'Сортировка по умолчанию (N=29 нечетный)'],
        ['FORMAT', 'B', 'Формат API ответа (29 mod 3 = 2 -> B)'],
        ['STAT', 'max', 'Агрегатная функция API (29 mod 4 = 1 -> max)']
    ];

    const paramTable = new Table({
        width: { size: 100, type: WidthType.PERCENTAGE },
        rows: paramRows.map((row, idx) => new TableRow({
            children: [
                createCell(row[0], idx === 0, 20),
                createCell(row[1], idx === 0, 30),
                createCell(row[2], idx === 0, 50)
            ]
        }))
    });

    const ktTable = new Table({
        width: { size: 100, type: WidthType.PERCENTAGE },
        rows: [
            ['КТ', 'Что показано', 'Отметка / Статус'],
            ['КТ 8.1', 'Каталог компьютерного клуба с подвалом (ФИО, группа, код занятия)', 'Выполнено успешно (200 OK)'],
            ['КТ 8.2', 'Работа формы POST /feedback, редирект PRG, защита @csrf', 'Выполнено успешно (302 -> 200)']
        ].map((row, idx) => new TableRow({
            children: [
                createCell(row[0], idx === 0, 20),
                createCell(row[1], idx === 0, 50),
                createCell(row[2], idx === 0, 30)
            ]
        }))
    });

    const obs3Table = new Table({
        width: { size: 100, type: WidthType.PERCENTAGE },
        rows: [
            ['Запрос', 'Код ответа', 'Что показано на экране'],
            ['/catalog?q=INV-00101', '200 OK', 'Таблица с 1 отфильтрованным компьютером INV-00101'],
            ['/catalog/7 (ITEMS)', '200 OK', 'Детальная карточка 7-го компьютера клуба (BTC-302)'],
            ['/catalog/8 (ITEMS+1)', '404 Not Found', 'Страница 404: запись с ID=8 отсутствует в хранилище'],
            ['/catalog/abc', '404 Not Found', 'Страница 404: параметр abc не прошел проверку whereNumber'],
            ['/catalog/code/VIP-102', '200 OK', 'Детальная страница компьютера с кодом VIP-102'],
            ['/catalog/code/vip-102', '404 Not Found', 'Страница 404: строчный код не прошел регулярное выражение [A-Z]{3}-[0-9]{3}']
        ].map((row, idx) => new TableRow({
            children: [
                createCell(row[0], idx === 0, 30),
                createCell(row[1], idx === 0, 20),
                createCell(row[2], idx === 0, 50)
            ]
        }))
    });

    const doc = new Document({
        sections: [{
            children: [
                createHeading("ОТЧЕТ ПО ЛАБОРАТОРНО-ПРАКТИЧЕСКОЙ РАБОТЕ № 8", HeadingLevel.TITLE),
                createParagraph("Тема: Основы разработки backend-приложения на Laravel"),
                createParagraph("Дисциплина: Основы backend-разработки"),
                createParagraph("Студент: Адиль | Группа: ИС-24 | Вариант 29 (N = 29, V = 29)"),
                createParagraph("Предметная область: Компьютерный клуб"),
                createParagraph("Родительская сущность: Zone (зона) | Основная сущность: Computer (компьютер)"),
                new Paragraph({ spacing: { before: 120, after: 120 } }),

                createHeading("1. Карточка персональных параметров и отметки КТ"),
                paramTable,
                new Paragraph({ spacing: { before: 120, after: 60 } }),
                createHeading("Отметки о прохождении контрольных точек (КТ)", HeadingLevel.HEADING_2),
                ktTable,
                new Paragraph({ spacing: { before: 120, after: 120 } }),

                createHeading("2. Результаты наблюдений"),
                createHeading("Наблюдение 1 (Информация о системе и маршрутах):", HeadingLevel.HEADING_2),
                createParagraph("Версия фреймворка: Laravel 13.35.0 (PHP 8.4.19)."),
                createParagraph("Первые 8 символов APP_KEY (после base64:): ncO13shZ."),
                createParagraph("Количество маршрутов: route:list показал 4 начальных маршрута."),
                createParagraph("Маршрут, объявленный не в web.php: маршрут health-check /up и storage/{path}. Маршрут /up объявляется фреймворком в bootstrap/app.php."),

                createHeading("Наблюдение 2 (Маршруты с параметрами и кириллица):", HeadingLevel.HEADING_2),
                createParagraph("/hello -> 200 OK, возвращает 'Привет, гость!'"),
                createParagraph("/hello/Adil -> 200 OK, возвращает 'Привет, Adil!'"),
                createParagraph("/hello/Адиль -> При стандартном ->whereAlpha('name') вернулся код 404 Not Found, так как whereAlpha использует регулярное выражение ^[a-zA-Z]+$ (только латиница)."),
                createParagraph("Решение: замена на ->where('name', '[\\p{L}]+'), где \\p{L} — Unicode Property 'любая буква'. После этого код ответа 200 OK ('Привет, Адиль!')."),

                createHeading("Наблюдение 3 (Каталог и ограничения параметров):", HeadingLevel.HEADING_2),
                obs3Table,
                createParagraph("Почему /catalog/abc дает 404, хотя в контроллере нет проверки на буквы? Проверка выполняется на этапе маршрутизации в routes/web.php благодаря ограничению ->whereNumber('id'). Строка 'abc' не является числом, поэтому роутер сразу возвращает 404."),

                createHeading("Наблюдение 4 (POST-форма, PRG и защита @csrf):", HeadingLevel.HEADING_2),
                createParagraph("1. При отправке формы во вкладке Network фиксируется два запроса: POST /feedback (302 Found) и затем GET /feedback (200 OK). Это реализация паттерна Post/Redirect/Get (PRG) для предотвращения повторной отправки данных при нажатии F5."),
                createParagraph("2. При удалении @csrf сервер возвращает ошибку 419 Page Expired (CSRF token mismatch). В HTML коде директива @csrf превращается в скрытое поле: <input type=\"hidden\" name=\"_token\" value=\"...\">."),
                createParagraph("3. При отправке текста <b>жирный</b> текст отображается буквально как теги <b>жирный</b>, так как Blade экранирует спецсимволы через htmlspecialchars(), защищая от XSS."),

                new Paragraph({ spacing: { before: 120, after: 120 } }),
                createHeading("3. Журнал ошибок"),
                createParagraph("1) Ошибка 404 при вводе кириллического имени в маршруте /hello/Адиль. Причина: whereAlpha не пропускает символы Unicode. Исправление: использование регулярного выражения [\\p{L}]+."),
                createParagraph("2) Ошибка 419 Page Expired при отправке формы обратной связи. Причина: отсутствие директивы @csrf в форме. Исправление: добавление @csrf внутрь тега <form>."),

                new Paragraph({ spacing: { before: 120, after: 120 } }),
                createHeading("4. Задания повышенного уровня ⭐"),
                createParagraph("1. Страница /catalog/stats: отображает статистику коллекции через avg(), min(), max(), count(). Маршрут объявлен до /{id} во избежание конфликтов."),
                createParagraph("2. Маршрут родителя /catalog/zone без параметров: выводит список уникальных игровых зон клуба (VIP Lounge, Общий зал, Bootcamp) со ссылками."),
                createParagraph("3. Кастомная страница 404: resources/views/errors/404.blade.php наследует общий макет и выводит ошибочно запрошенный путь через request()->path()."),

                new Paragraph({ spacing: { before: 120, after: 120 } }),
                createHeading("5. Ответы на контрольные вопросы"),
                createParagraph("1. Путь запроса GET /catalog/2: public/index.php -> bootstrap/app.php -> routes/web.php (проверка whereNumber) -> CatalogController::show(2) -> resources/views/catalog/show.blade.php (макет layouts/app.blade.php) -> HTTP 200 браузеру."),
                createParagraph("2. Почему .env не хранится в Git? Файл .env содержит конфиденциальные ключи шифрования, пароли к БД и локальные настройки. .env.example нужен как публичный образец конфигурации."),
                createParagraph("3. Чем config('lab.code') отличается от env('LAB_CODE')? Функция config() берет значение из config/*.php, которое кешируется. После php artisan config:cache функция env() всегда возвращает null, а config() продолжает работать."),
                createParagraph("4. Чем отличаются $request->query('x') и $request->input('x')? query() ищет только в GET-параметрах URL строки, а input() проверяет тело запроса (POST, JSON) и затем строку URL."),
                createParagraph("5. Почему после POST возвращается redirect(), а не view()? Чтобы предотвратить повторную отправку формы при обновлении страницы (паттерн PRG)."),
                createParagraph("6. Что такое CSRF-атака и как @csrf защищает? Это атака подделки межсайтового запроса. @csrf внедряет одноразовый токен сессии, который злоумышленник не может узнать из-за политики Same-Origin."),
                createParagraph("7. Что будет, если убрать whereNumber('id') и открыть /catalog/abc? PHP выбросит ошибку TypeError (код 500) при строгой типизации int $id в аргументе метода show."),

                new Paragraph({ spacing: { before: 120, after: 120 } }),
                createHeading("6. История коммитов Git (git-log-lpr8.txt)"),
                ...createCodeBlock(gitLog)
            ]
        }]
    });

    const buffer = await Packer.toBuffer(doc);
    fs.writeFileSync(path.join(__dirname, '..', 'ОТЧЕТ_ЛПР_08_Адиль.docx'), buffer);
    console.log("ОТЧЕТ_ЛПР_08_Адиль.docx успешно создан!");
}

// -------------------------------------------------------------
// ГЕНЕРАЦИЯ ОТЧЕТА ПО ЛПР № 9
// -------------------------------------------------------------
async function generateLab9Docx() {
    const gitLog = fs.readFileSync(path.join(__dirname, '..', 'git-log-lpr9.txt'), 'utf8');

    const paramRows = [
        ['Параметр', 'Значение', 'Описание для ЛПР № 9'],
        ['DB', 'lpr_adils', 'Имя базы данных MySQL'],
        ['PER_PAGE', '4', 'Количество компьютеров на страницу (3 + (29 mod 4) = 4)'],
        ['MIN_LEN', '4', 'Минимальная длина inventory_number (2 + (29 mod 3) = 4)'],
        ['STR_LEN', '187', 'Новая длина inventory_number после миграции (100 + 3*29 = 187)'],
        ['SEED', '41', 'Количество компьютеров, создаваемых сидером (12 + 29 = 41)'],
        ['PARENTS', '5', 'Количество игровых зон в сидере (3 + (29 mod 3) = 5)'],
        ['ON_DELETE', 'cascade', 'Каскадное удаление (N=29 нечетный -> cascadeOnDelete)'],
        ['SORT', 'по основному полю А->Я', 'Сортировка по умолчанию (N=29 нечетный)'],
        ['M:N', 'Game', 'Сущность для связи многие-ко-многим (игры)']
    ];

    const paramTable = new Table({
        width: { size: 100, type: WidthType.PERCENTAGE },
        rows: paramRows.map((row, idx) => new TableRow({
            children: [
                createCell(row[0], idx === 0, 20),
                createCell(row[1], idx === 0, 30),
                createCell(row[2], idx === 0, 50)
            ]
        }))
    });

    const ktTable = new Table({
        width: { size: 100, type: WidthType.PERCENTAGE },
        rows: [
            ['КТ', 'Что показано', 'Отметка / Статус'],
            ['КТ 9.1', 'Список компьютеров с пагинацией PER_PAGE=4, поиск, результат Наблюдения 4 (N+1)', 'Выполнено успешно (200 OK)'],
            ['КТ 9.2', 'Полный цикл CRUD (создание, редактирование, удаление), валидация и ошибки', 'Выполнено успешно']
        ].map((row, idx) => new TableRow({
            children: [
                createCell(row[0], idx === 0, 20),
                createCell(row[1], idx === 0, 50),
                createCell(row[2], idx === 0, 30)
            ]
        }))
    });

    const obs5Table = new Table({
        width: { size: 100, type: WidthType.PERCENTAGE },
        rows: [
            ['Поле', 'Введенное значение', 'Сообщение об ошибке', 'Сработавшее правило'],
            ['inventory_number', 'INV', 'Инвентарный номер должен содержать не менее 4 символов (MIN_LEN = 4).', 'min:4 (MIN_LEN - 1)'],
            ['zone_id', '99999 (подмена F12)', 'Выбранная игровая зона не найдена в базе данных.', 'exists:zones,id'],
            ['code', 'abc-123', 'Код должен строго соответствовать формату AAA-999.', 'regex:/^[A-Z]{3}-[0-9]{3}$/'],
            ['ram_gb', '0', 'Объем оперативной памяти должен быть не менее 1 ГБ.', 'min:1'],
            ['hour_price', '-50', 'Стоимость часа аренды не может быть отрицательной.', 'min:0']
        ].map((row, idx) => new TableRow({
            children: [
                createCell(row[0], idx === 0, 20),
                createCell(row[1], idx === 0, 25),
                createCell(row[2], idx === 0, 35),
                createCell(row[3], idx === 0, 20)
            ]
        }))
    });

    const doc = new Document({
        sections: [{
            children: [
                createHeading("ОТЧЕТ ПО ЛАБОРАТОРНО-ПРАКТИЧЕСКОЙ РАБОТЕ № 9", HeadingLevel.TITLE),
                createParagraph("Тема: Работа с базой данных и Eloquent ORM"),
                createParagraph("Дисциплина: Основы backend-разработки"),
                createParagraph("Студент: Адиль | Группа: ИС-24 | Вариант 29 (N = 29, V = 29)"),
                createParagraph("Предметная область: Компьютерный клуб"),
                createParagraph("Связь: Zone (один) -> Computer (многие) | M:N: Computer <-> Game"),
                new Paragraph({ spacing: { before: 120, after: 120 } }),

                createHeading("1. Карточка персональных параметров и контрольные точки"),
                paramTable,
                new Paragraph({ spacing: { before: 120, after: 60 } }),
                createHeading("Отметки о прохождении контрольных точек (КТ)", HeadingLevel.HEADING_2),
                ktTable,
                new Paragraph({ spacing: { before: 120, after: 120 } }),

                createHeading("2. Результаты наблюдений 1–5"),
                createHeading("Наблюдение 1 (Подключение к БД MySQL):", HeadingLevel.HEADING_2),
                createParagraph("Вывод php artisan db:show показал версию MySQL 8.4.8."),
                createParagraph("Количество таблиц: 9 таблиц (cache, cache_locks, failed_jobs, job_batches, jobs, migrations, password_reset_tokens, sessions, users)."),
                createParagraph("Откуда они взялись: это стандартные таблицы фреймворка Laravel из каталога database/migrations плюс служебная таблица migrations."),

                createHeading("Наблюдение 2 (Внешний ключ и ON DELETE):", HeadingLevel.HEADING_2),
                createParagraph("Запрос: SHOW CREATE TABLE computers;"),
                createParagraph("Имя ограничения внешнего ключа: computers_zone_id_foreign."),
                createParagraph("Действие внешнего ключа: ON DELETE CASCADE ON UPDATE CASCADE (в строгом соответствии с формулой варианта для нечетного N = 29)."),

                createHeading("Наблюдение 3 (Сидер и удаление родителя):", HeadingLevel.HEADING_2),
                createParagraph("Computer::count() = 41 (совпадает с SEED = 41)."),
                createParagraph("Zone::count() = 5 (совпадает с PARENTS = 5)."),
                createParagraph("Zone::withCount('computers')->get()->pluck('computers_count', 'name'): VIP Lounge: 8, Общий зал: 10, Bootcamp: 8, Cyber Arena: 8, Retro Corner: 7 (у каждого родителя >= 2 записей)."),
                createParagraph("При удалении первой зоны Zone::first()->delete() количество компьютеров уменьшилось с 41 до 33 (на 8 записей). Так как поведение внешнего ключа установлено в CASCADE, база данных автоматически удалила все дочерние компьютеры, принадлежавшие удаленной зоне."),

                createHeading("Наблюдение 4 (Проблема N+1 и жадная загрузка):", HeadingLevel.HEADING_2),
                createParagraph("Без жадной загрузки (без with('zone')) при PER_PAGE = 4 выполняется 6 SQL-запросов (2 запроса пагинатора + 4 отдельных запроса на каждую строку зоны). Формула: 2 + PER_PAGE = 6."),
                createParagraph("С жадной загрузкой with('zone') выполняется всего 3 SQL-запроса (2 запроса пагинатора + 1 пакетный запрос WHERE zone_id IN (...)). Жадная загрузка полностью решает проблему N+1."),

                createHeading("Наблюдение 5 (Ошибки валидации):", HeadingLevel.HEADING_2),
                obs5Table,
                createParagraph("Зачем нужна серверная проверка exists, если родитель выбирается из выпадающего списка? Пользователь или злоумышленник может легко изменить значение value в HTML-разметке через DevTools (F12) или отправить поддельный POST-запрос. Серверная проверка гарантирует целостность базы данных."),

                new Paragraph({ spacing: { before: 120, after: 120 } }),
                createHeading("3. Журнал ошибок"),
                createParagraph("1) SQLSTATE[HY000]: General error: 1 no such table: computers при выполнении тестов. Причина: в phpunit.xml по умолчанию использовалась sqlite база данных в памяти без автоматического применения миграций. Способ устранения: переключение тестового окружения на основную БД MySQL lpr_adils."),
                createParagraph("2) Ошибка нарушения уникальности поля code при повторном запуске тестов добавления. Причина: статически захардкоженные тестовые коды конфликтовали с существующими. Способ устранения: генерация уникальных случайных кодов в тестах и использование Rule::unique()->ignore($id)."),

                new Paragraph({ spacing: { before: 120, after: 120 } }),
                createHeading("4. Задания повышенного уровня ⭐"),
                createParagraph("⭐ 1. Фильтры списка: в интерфейсе каталога реализованы выпадающий список фильтрации по зоне (?zone_id=) и переключатель по логическому полю VIP-статуса (?is_vip=)."),
                createParagraph("⭐ 2. Страницы родителя: на странице /catalog/zone отображаются все 5 игровых зон с точным количеством компьютеров в каждой (через withCount). Реализована кнопка каскадного удаления с предупреждением о количестве удаляемых связанных записей."),
                createParagraph("⭐ 3. Связь 'многие-ко-многим' (M:N): создана модель Game (игры) и промежуточная таблица computer_game. В формах добавления и редактирования ПК выводятся чекбоксы установленных игр, при сохранении используется метод $computer->games()->sync()."),

                new Paragraph({ spacing: { before: 120, after: 120 } }),
                createHeading("5. Ответы на контрольные вопросы"),
                createParagraph("1. Что хранится в таблице migrations? В ней хранятся имена всех успешно выполненных миграций и номер пакета (batch). Batch группирует миграции, выполненные за одну команду. Команда php artisan migrate:rollback отменяет миграции последнего пакета (последнего batch)."),
                createParagraph("2. Почему нельзя изменять уже выполненную миграцию? Потому что у других разработчиков и на сервере эта миграция уже помечена как выполненная. Изменение старого файла приведет к рассинхронизации схемы БД. Любые изменения нужно вносить новой миграцией (например, add_description_to_computers_table)."),
                createParagraph("3. От чего защищает $fillable? Защищает от уязвимости Mass Assignment (массового заполнения), когда злоумышленник может передать в форму скрытые поля (например, is_admin=1). В модель попадут только поля, явно перечисленные в массиве $fillable."),
                createParagraph("4. Как Laravel по маршруту /catalog/{computer}/edit находит запись в БД? Механизм Route Model Binding автоматически делает запрос Computer::findOrFail($id). Если записи с таким ID нет, Laravel автоматически выбрасывает ошибку 404 Not Found."),
                createParagraph("5. Чем $request->all() отличается от $request->validated()? $request->all() возвращает абсолютно все переданные входные данные, включая небезопасные или лишние. $request->validated() возвращает только те поля, которые успешно прошли правила валидации в FormRequest."),
                createParagraph("6. Что делает Rule::unique(...)->ignore($id)? Исключает текущую запись из проверки уникальности при обновлении. Без ignore валидация будет падать с ошибкой 'Запись с таким полем уже существует', так как проверяет саму же себя."),
                createParagraph("7. Чем restrictOnDelete() отличается от cascadeOnDelete()? restrict блокирует удаление родителя при наличии связанных дочерних записей (выбрасывает ошибку), а cascade автоматически удаляет все связанные дочерние записи вместе с родителем. Для компьютерного клуба при сносе или закрытии игровой зоны логично удалять или переносить закрепленные за ней ПК."),

                new Paragraph({ spacing: { before: 120, after: 120 } }),
                createHeading("6. История коммитов Git (git-log-lpr9.txt)"),
                ...createCodeBlock(gitLog)
            ]
        }]
    });

    const buffer = await Packer.toBuffer(doc);
    fs.writeFileSync(path.join(__dirname, '..', 'ОТЧЕТ_ЛПР_09_Адиль.docx'), buffer);
    console.log("ОТЧЕТ_ЛПР_09_Адиль.docx успешно создан!");
}

async function run() {
    await generateLab8Docx();
    await generateLab9Docx();
}

run();
