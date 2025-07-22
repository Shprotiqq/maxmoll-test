<?php

namespace App\DTOs\Order;

final readonly class ResumeOrderDTO
{
    public function __construct(
        public int $order_id
    )
    {
    }
}
