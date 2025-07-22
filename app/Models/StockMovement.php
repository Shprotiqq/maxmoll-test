<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Класс StockMovement
 *
 * Модель для работы с историями операций в исторической таблице stock_movements.
 */
class StockMovement extends Model
{
    protected $table = 'stock_movements';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'stock_before',
        'stock_after',
        'operation',
        'created_at',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
