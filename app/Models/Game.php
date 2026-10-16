<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'genre',
    ];

    /**
     * Связь «многие-ко-многим»: игра установлена на многих компьютерах.
     */
    public function computers(): BelongsToMany
    {
        return $this->belongsToMany(Computer::class);
    }
}
