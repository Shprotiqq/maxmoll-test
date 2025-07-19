<?php

namespace App\Enums;

enum StockOperationType: string
{
    case INITIAL = 'Первоначальный остаток';
    case ORDER_CREATED = 'Заказ создан';
    case ORDER_CANCELED = 'Заказ отменен';
    case ORDER_RESUMED = 'Заказ возвращен в работу';
    case MANUAL_INCREASE = 'Количество товара увеличилось';
    case MANUAL_DECREASE = 'Количество товара уменьшилось';
}
