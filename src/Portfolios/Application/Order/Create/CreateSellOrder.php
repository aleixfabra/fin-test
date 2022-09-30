<?php

declare(strict_types=1);

namespace App\Portfolios\Application\Order\Create;

use App\Portfolios\Application\Allocation\Find\FindAllocation;
use App\Portfolios\Application\Portfolio\Find\FindPortfolio;
use App\Portfolios\Domain\Allocation\AllocationNotFoundException;
use App\Portfolios\Domain\Allocation\SellOrderExceededAllocationSharesException;
use App\Portfolios\Domain\Order\OrderRepository;

final class CreateSellOrder extends CreateOrder
{
    private FindAllocation $findAllocation;

    public function __construct(
        FindPortfolio $findPortfolio,
        FindAllocation $findAllocation,
        OrderRepository $orderRepository
    ) {
        parent::__construct(
            $findPortfolio,
            $orderRepository
        );

        $this->findAllocation = $findAllocation;
    }

    /**
     * @throws SellOrderExceededAllocationSharesException
     * @throws AllocationNotFoundException
     */
    protected function ensuresCreateOrderCouldBeExecuted(
        CreateOrderRequest $createOrderRequest
    ): void {
        $allocation = $this->findAllocation->__invoke($createOrderRequest->getAllocationId());

        if ($createOrderRequest->getShares() > $allocation->getShares()) {
            throw new SellOrderExceededAllocationSharesException();
        }
    }
}
