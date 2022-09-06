<?php

declare(strict_types=1);

namespace App\Tests\Domain\Order;

use App\Domain\Order\Order;
use App\Domain\Portfolio\Portfolio;
use App\Tests\Domain\Portfolio\PortfolioMother;

final class OrderMother
{
    private const DEFAULT_ID = 1;
    private const DEFAULT_ALLOCATION_ID = 1;
    private const DEFAULT_SHARES = 3;
    private const DEFAULT_TYPE = 'buy';

    public static function create(
        ?int $id = null,
        ?Portfolio $portfolio = null,
        ?int $allocationId = null,
        ?int $shares = null,
        ?string $type = null
    ): Order {
        return new Order(
            $id ?? self::DEFAULT_ID,
            $portfolio ?? PortfolioMother::create(),
            $allocationId ?? self::DEFAULT_ALLOCATION_ID,
            $shares ?? self::DEFAULT_SHARES,
            $type ?? self::DEFAULT_TYPE
        );
    }

    public static function withPortfolio(Portfolio $portfolio): Order
    {
        return self::create(
            null,
            $portfolio
        );
    }

    public static function typeBuy(Portfolio $portfolio): Order
    {
        return self::create(
            null,
            $portfolio,
            null,
            null,
            'buy'
        );
    }

    public static function typeSell(Portfolio $portfolio): Order
    {
        return self::create(
            null,
            $portfolio,
            null,
            null,
            'sell'
        );
    }

    public static function unknownType(): Order
    {
        return self::create(
            null,
            null,
            null,
            null,
            'unknown_type'
        );
    }
}
