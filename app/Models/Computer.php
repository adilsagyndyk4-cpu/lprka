<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Computer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'inventory_number',
        'zone_id',
        'cpu',
        'ram_gb',
        'hour_price',
        'is_vip',
    ];

    protected function casts(): array
    {
        return [
            'ram_gb'     => 'integer',
            'hour_price' => 'decimal:2',
            'is_vip'     => 'boolean',
        ];
    }

    /**
     * Связь «многие к одному»: компьютер принадлежит зоне.
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
