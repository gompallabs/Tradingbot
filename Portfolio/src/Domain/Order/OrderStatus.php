<?php

declare(strict_types=1);

namespace App\Domain\Order;

enum OrderStatus: string
{
    case filled = 'filled';
    case partial_fill = 'partial_fill';
    case expired = 'expired';
    case failed = 'failed';
}