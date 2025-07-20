<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $primaryKey = [
        'product_id',
        'warehouse_id'
    ];

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'stock'
    ];

    public ?string $operation_type = null;
    public ?string $operation_id = null;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
