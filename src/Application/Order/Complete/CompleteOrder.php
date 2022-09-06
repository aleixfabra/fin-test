<?php

declare(strict_types=1);

namespace App\Application\Order\Complete;

use App\Application\Order\Find\FindOrder;
use App\Domain\Allocation\AllocationRepository;
use App\Domain\Order\Order;
use App\Domain\Order\OrderNotFoundException;
use App\Domain\Order\OrderRepository;

abstract class CompleteOrder
{
    private FindOrder $findOrder;

    private OrderRepository $orderRepository;

    protected AllocationRepository $allocationRepository;

    public function __construct(
        FindOrder $findOrder,
        OrderRepository $orderRepository,
        AllocationRepository $allocationRepository
    ) {
        $this->findOrder = $findOrder;
        $this->orderRepository = $orderRepository;
        $this->allocationRepository = $allocationRepository;
    }

    /**
     * @throws OrderNotFoundException
     */
    public function __invoke(int $orderId): void
    {
        $order = $this->findOrder->__invoke($orderId);

        if (!$this->isValidType($order)) {
            return;
        }

        $this->executeOrder($order);

        $this->markOrderAsCompleted($order);
    }

    abstract protected function isValidType(Order $order): bool;

    abstract protected function executeOrder(Order $order): void;

    protected function markOrderAsCompleted(Order $order): void
    {
        $order->completed();
        $this->orderRepository->save($order);
    }
}
