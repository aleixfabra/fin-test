<?php

declare(strict_types=1);

namespace App\Tests\Domain\Portfolio;

use App\Portfolios\Domain\Allocation\Allocation;
use App\Portfolios\Domain\Portfolio\Portfolio;
use App\Tests\Domain\Allocation\AllocationMother;

final class PortfolioMother
{
    private const DEFAULT_ID = 1;

    public static function create(?int $id = null): Portfolio
    {
        return new Portfolio($id ?? self::DEFAULT_ID);
    }

    /**
     * @param Allocation[] $allocations
     */
    public static function withAllocations(?int $id = null, ?array $allocations = null): Portfolio
    {
        $portfolio = self::create($id);
        $portfolio->addAllocations($allocations ?? self::defaultAllocations($portfolio));

        return $portfolio;
    }

    /**
     * @return Allocation[]
     */
    private static function defaultAllocations(Portfolio $portfolio): array
    {
        return [
            AllocationMother::create(1, 3, $portfolio),
            AllocationMother::create(2, 4, $portfolio),
        ];
    }
}
