<?php

namespace App\Observers;

use App\Enums\StockOperationType;
use App\Models\Stock;
use App\Models\StockMovement;

final class StockObserver
{
    public function created(Stock $stock): void
    {
        $this->recordMovement(
            stock: $stock,
            amount: $stock->stock,
            operationType: StockOperationType::INITIAL->value,
            notes: 'Первоначальный остаток',
        );
    }

    public function updated(Stock $stock): void
    {
        $original = $stock->getOriginal('stock');
        $current = $stock->stock;
        $difference = $current - $original;

        if ($difference === 0) {
            return;
        }

        $operationType = $stock->operation_type
            ?? ($difference > 0
                ? StockOperationType::MANUAL_INCREASE
                : StockOperationType::MANUAL_DECREASE
            );

        $this->recordMovement(
            stock: $stock,
            amount: $difference,
            operationType: $operationType,
            notes: $stock->operation_notes ?? 'Корректировка остатка товара',
        );
    }


    private function recordMovement(Stock $stock, int $amount, string $operationType, ?string $notes = null): void
    {
        StockMovement::query()->create([
            'product_id' => $stock->product_id,
            'warehouse_id' => $stock->warehouse_id,
            'amount' => $amount,
            'operation_type' => $operationType,
            'operation_id' => $stock->operation_id,
            'notes' => $notes ?? $this->defaultNotes($operationType, $stock),
        ]);
    }

    private function defaultNotes(string $operationType, Stock $stock): string
    {
        return match ($operationType) {
            StockOperationType::ORDER_CREATED => "Заказ #{$stock->operation_id} создан",
            StockOperationType::ORDER_CANCELED => "Заказ #{$stock->operation_id} отменен",
            StockOperationType::ORDER_RESUMED => "Заказ #{$stock->operation_id} возвращен в работу",
            default => 'Товар перемещен'
        };
    }
}
