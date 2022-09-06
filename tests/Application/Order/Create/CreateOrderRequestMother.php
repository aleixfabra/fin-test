<?php

declare(strict_types=1);

namespace App\Tests\Application\Order\Create;

use App\Application\Order\Create\CreateOrderRequest;
use App\Domain\Order\Order;

final class CreateOrderRequestMother
{
    private const DEFAULT_ORDER_ID = 1;
    private const DEFAULT_PORTFOLIO_ID = 1;
    private const DEFAULT_ALLOCATION_ID = 1;
    private const DEFAULT_SHARES = 3;
    private const DEFAULT_TYPE = 'buy';

    public static function create(
        ?int $orderId = null,
        ?int $portfolioId = null,
        ?int $allocationId = null,
        ?int $shares = null,
        ?string $type = null
    ): CreateOrderRequest {
        return new CreateOrderRequest(
            $orderId ?? self::DEFAULT_ORDER_ID,
            $portfolioId ?? self::DEFAULT_PORTFOLIO_ID,
            $allocationId ?? self::DEFAULT_ALLOCATION_ID,
            $shares ?? self::DEFAULT_SHARES,
            $type ?? self::DEFAULT_TYPE
        );
    }

    public static function fromOrder(Order $order): CreateOrderRequest
    {
        return self::create(
            $order->getId(),
            $order->getPortfolio()->getId(),
            $order->getAllocationId(),
            $order->getShares(),
            $order->getType()
        );
    }
}
