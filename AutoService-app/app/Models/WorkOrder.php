<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrder extends Model
{
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'open' => 'Abierta',
            'in_progress' => 'En proceso',
            'ready' => 'Lista para entrega',
            'delivered' => 'Entregada',
            default => $this->status,
        };
    }

    public function items(): HasMany
    {
        return $this->hasMany(WorkOrderItem::class);
    }
}