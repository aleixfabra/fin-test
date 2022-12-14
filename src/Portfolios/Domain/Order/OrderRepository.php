<?php

declare(strict_types=1);

namespace App\Portfolios\Domain\Order;

interface OrderRepository
{
    public function save(Order $order): void;

    public function search(int $id): ?Order;
}
