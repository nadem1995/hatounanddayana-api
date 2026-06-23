<?php

namespace App\Models;

use App\Models\Product;
use App\Models\CurrencyRate;
use App\Services\Admin\CurrencyService;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'type',
        'value',
        'start_at',
        'end_at',
        'status',
    ];

    protected $casts = [
        'status'   => 'boolean',
        'start_at' => 'datetime',
        'end_at'  => 'datetime',
        'value'   => 'float',
    ];

    protected $appends = ['value_syp'];

    /* ========================
     | Relationships
     ========================*/
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /* ========================
     | Business Logic
     ========================*/
    public function isValid(): bool
    {
        if (! $this->status) {
            return false;
        }

        $now = now();

        return (! $this->start_at || $this->start_at->lte($now)) &&
            (! $this->end_at   || $this->end_at->gte($now));
    }

    /* ========================
     | Accessors
     ========================*/
    public function getValueSypAttribute(): ?float
    {
        if ($this->type !== 'fixed') {
            return null;
        }

        $rate = CurrencyRate::value('SYP_rate');

        return round($this->value * $rate, 2);
    }



    /* ========================
     | Formatter (API-ready)
     ========================*/
    public function format(): array
    {
        $value = match ($this->type) {
            'fixed' => [
                'usd' => $this->value,
                'syp' => $this->value_syp,
            ],
            'percentage' => $this->value,
        };

        return [
            'id'       => $this->id,
            'type'     => $this->type,
            'value'    => $value,
        ];
    }
}
