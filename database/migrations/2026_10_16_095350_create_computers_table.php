<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Миграция: таблица computers (основная сущность — Компьютер).
     * Вариант 29: ITEMS = 7, CODE = [A-Z]{3}-[0-9]{3},
     * STR_LEN = 187, MIN_LEN = 4, ON_DELETE = cascade.
     */
    public function up(): void
    {
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 7)->unique()->comment('Код формата AAA-999');
            $table->string('inventory_number', 20)->unique()->comment('Инвентарный номер (основное строковое поле)');
            $table->foreignId('zone_id')
                ->constrained()
                ->cascadeOnDelete()      // ON_DELETE = cascade (N = 29, нечётный)
                ->cascadeOnUpdate();
            $table->string('cpu', 187)->comment('Процессор (STR_LEN = 187)');
            $table->unsignedSmallInteger('ram_gb')->comment('Оперативная память, ГБ');
            $table->decimal('hour_price', 8, 2)->comment('Стоимость часа аренды, ₽');
            $table->boolean('is_vip')->default(false)->comment('VIP-место');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('computers');
    }
};
