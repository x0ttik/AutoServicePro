<?php

//ya esta
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    protected $fillable = ['plate', 'make', 'model', 'model_year'];
    
    protected function casts(): array
    {
        return [
            'model_year' => 'integer',
        ];
    }


    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
