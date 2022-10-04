<?php

declare(strict_types=1);

namespace App\Tests\Portfolios\Domain\Allocation;

use App\Portfolios\Domain\Allocation\Allocation;
use App\Portfolios\Domain\Portfolio\Portfolio;
use App\Tests\Portfolios\Domain\Portfolio\PortfolioMother;

final class AllocationMother
{
    private const DEFAULT_ID = 1;
    private const DEFAULT_SHARES = 2;

    public static function create(
        ?int $id = null,
        ?int $shares = null,
        ?Portfolio $portfolio = null
    ): Allocation {
        return new Allocation(
            $id ?? self::DEFAULT_ID,
            $shares ?? self::DEFAULT_SHARES,
            $portfolio ?? PortfolioMother::create()
        );
    }

    public static function withPortfolio(Portfolio $portfolio): Allocation
    {
        return self::create(null, null, $portfolio);
    }

    public static function withShares(
        Portfolio $portfolio,
        int $shares
    ): Allocation {
        return self::create(null, $shares, $portfolio);
    }
}
