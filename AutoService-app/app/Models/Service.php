<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected function casts(): array
    {
        return [
            'base_price_cents' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
