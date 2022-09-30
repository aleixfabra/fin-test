<?php

declare(strict_types=1);

namespace App\Portfolios\Application\Order\Create;

use App\Portfolios\Domain\Order\Order;
use InvalidArgumentException;

final class CreateOrderFactory
{
    private CreateBuyOrder $createBuyOrder;

    private CreateSellOrder $createSellOrder;

    public function __construct(
        CreateBuyOrder $createBuyOrder,
        CreateSellOrder $createSellOrder
    ) {
        $this->createBuyOrder = $createBuyOrder;
        $this->createSellOrder = $createSellOrder;
    }

    public function __invoke(string $type): CreateOrder
    {
        switch ($type) {
            case Order::TYPE_BUY:
                return $this->createBuyOrder;
            case Order::TYPE_SELL:
                return $this->createSellOrder;
            default:
                throw new InvalidArgumentException();
        }
    }
}
