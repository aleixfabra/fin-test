<?php

namespace App\Infrastructure\Order\Persistence;

use App\Domain\Order\Order;
use App\Domain\Order\OrderRepository;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;

final class DoctrineOrderRepository extends DoctrineRepository implements OrderRepository
{
    public function save(Order $order): void
    {
        $this->persist($order);
    }

    public function search(int $id): ?Order
    {
        return $this->repository(Order::class)->find($id);
    }
}
