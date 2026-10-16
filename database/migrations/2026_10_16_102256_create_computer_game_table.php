<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Промежуточная таблица computer_game для связи многие-ко-многим (M:N).
     * ⭐ Задание повышенного уровня 3.
     */
    public function up(): void
    {
        Schema::create('computer_game', function (Blueprint $table) {
            $table->foreignId('computer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->primary(['computer_id', 'game_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('computer_game');
    }
};
