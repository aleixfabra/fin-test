<?php

declare(strict_types=1);

namespace App\Portfolios\Application\Order\Complete;

use App\Portfolios\Domain\Allocation\Allocation;
use App\Portfolios\Domain\Order\Order;

final class CompleteBuyOrder extends CompleteOrder
{
    protected function isValidType(Order $order): bool
    {
        return $order->isTypeBuyAndNotCompleted();
    }

    protected function executeOrder(Order $order): void
    {
        $allocationExists = $this->allocationRepository->search($order->getAllocationId());

        if ($allocationExists) {
            $allocation = $allocationExists;
            $allocation->addShares($order->getShares());
        } else {
            $allocation = new Allocation(
                $order->getAllocationId(),
                $order->getShares(),
                $order->getPortfolio()
            );
        }

        $this->allocationRepository->save($allocation);
    }
}
