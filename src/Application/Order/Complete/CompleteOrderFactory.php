<?php

declare(strict_types=1);

namespace App\Application\Order\Complete;

use App\Application\Order\Find\FindOrder;
use App\Domain\Order\Order;
use App\Domain\Order\OrderNotFoundException;
use InvalidArgumentException;

final class CompleteOrderFactory
{
    private FindOrder $findOrder;

    private CompleteBuyOrder $completeBuyOrder;

    private CompleteSellOrder $completeSellOrder;

    public function __construct(
        FindOrder $findOrder,
        CompleteBuyOrder $completeBuyOrder,
        CompleteSellOrder $completeSellOrder
    ) {
        $this->findOrder = $findOrder;
        $this->completeBuyOrder = $completeBuyOrder;
        $this->completeSellOrder = $completeSellOrder;
    }

    /**
     * @throws OrderNotFoundException
     */
    public function __invoke(int $id): CompleteOrder
    {
        $order = $this->findOrder->__invoke($id);

        switch ($order->getType()) {
            case Order::TYPE_BUY:
                return $this->completeBuyOrder;
            case Order::TYPE_SELL:
                return $this->completeSellOrder;
            default:
                throw new InvalidArgumentException();
        }
    }
}
