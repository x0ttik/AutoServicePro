<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    protected $fillable = [
        'service_id',
        'service_name',
        'unit_price_cents',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'unit_price_cents' => 'integer',
            'quantity' => 'integer',
        ];
    }

    public function subtotalCents(): int
    {
        return $this->unit_price_cents * $this->quantity;
    }
}