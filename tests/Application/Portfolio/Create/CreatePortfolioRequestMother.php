<?php

declare(strict_types=1);

namespace App\Tests\Application\Portfolio\Create;

use App\Portfolios\Application\Portfolio\Create\CreatePortfolioRequest;
use App\Portfolios\Domain\Portfolio\Portfolio;

final class CreatePortfolioRequestMother
{
    public static function create(
        int $portfolioId,
        array $allocations
    ): CreatePortfolioRequest {
        return new CreatePortfolioRequest(
            $portfolioId,
            $allocations
        );
    }

    public static function fromPortfolio(Portfolio $portfolio): CreatePortfolioRequest
    {
        return self::create(
            $portfolio->getId(),
            $portfolio->getAllocationsToPrimitives()
        );
    }
}
