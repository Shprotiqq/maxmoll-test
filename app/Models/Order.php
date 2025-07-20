<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'customer',
        'warehouse_id',
        'status',
        'created_at',
        'completed_at',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'created_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public $timestamps = false;

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
