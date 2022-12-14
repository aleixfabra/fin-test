<?php

declare(strict_types=1);

namespace App\Portfolios\Application\Order\Complete;

use App\Portfolios\Application\Allocation\Find\FindAllocation;
use App\Portfolios\Application\Order\Find\FindOrder;
use App\Portfolios\Domain\Allocation\Allocation;
use App\Portfolios\Domain\Allocation\AllocationNotFoundException;
use App\Portfolios\Domain\Allocation\AllocationRepository;
use App\Portfolios\Domain\Allocation\SellOrderExceededAllocationSharesException;
use App\Portfolios\Domain\Order\Order;
use App\Portfolios\Domain\Order\OrderRepository;

final class CompleteSellOrder extends CompleteOrder
{
    private FindAllocation $findAllocation;

    public function __construct(
        FindOrder $findOrder,
        FindAllocation $findAllocation,
        OrderRepository $orderRepository,
        AllocationRepository $allocationRepository
    ) {
        parent::__construct(
            $findOrder,
            $orderRepository,
            $allocationRepository
        );

        $this->findAllocation = $findAllocation;
    }

    protected function isValidType(Order $order): bool
    {
        return $order->isTypeSellAndNotCompleted();
    }

    /**
     * @throws AllocationNotFoundException
     * @throws SellOrderExceededAllocationSharesException
     */
    protected function executeOrder(Order $order): void
    {
        $allocation = $this->findAllocation->__invoke($order->getAllocationId());

        $this->ensuresSellOrderCouldBeExecuted($order, $allocation);

        if ($order->getShares() === $allocation->getShares()) {
            $this->allocationRepository->delete($allocation);
        } else {
            $allocation->removeShares($order->getShares());
            $this->allocationRepository->save($allocation);
        }
    }

    /**
     * @throws SellOrderExceededAllocationSharesException
     */
    protected function ensuresSellOrderCouldBeExecuted(Order $order, Allocation $allocation): void
    {
        if ($order->getShares() > $allocation->getShares()) {
            throw new SellOrderExceededAllocationSharesException();
        }
    }
}
