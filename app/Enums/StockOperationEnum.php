<?php

namespace App\Enums;

enum StockOperationEnum: string
{
    case INCREMENT = "increment";

    case DECREMENT = "decrement";
}
