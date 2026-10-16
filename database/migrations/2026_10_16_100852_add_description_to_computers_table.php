<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ЛПР № 9: Часть 3. Изменение таблицы.
     * Добавление столбца description и изменение длины основного строкового поля inventory_number на STR_LEN = 187.
     */
    public function up(): void
    {
        Schema::table('computers', function (Blueprint $table) {
            $table->text('description')->nullable()->after('inventory_number');
            $table->string('inventory_number', 187)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('computers', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->string('inventory_number', 20)->change();
        });
    }
};
