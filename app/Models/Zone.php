<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'floor',
        'description',
    ];

    /**
     * Связь «один ко многим»: у одной зоны много компьютеров.
     */
    public function computers(): HasMany
    {
        return $this->hasMany(Computer::class);
    }
}
