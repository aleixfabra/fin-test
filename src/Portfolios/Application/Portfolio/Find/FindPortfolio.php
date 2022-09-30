<?php

declare(strict_types=1);

namespace App\Portfolios\Application\Portfolio\Find;

use App\Portfolios\Domain\Portfolio\Portfolio;
use App\Portfolios\Domain\Portfolio\PortfolioNotFoundException;
use App\Portfolios\Domain\Portfolio\PortfolioRepository;

final class FindPortfolio
{
    private PortfolioRepository $portfolioRepository;

    public function __construct(PortfolioRepository $portfolioRepository)
    {
        $this->portfolioRepository = $portfolioRepository;
    }

    /**
     * @throws PortfolioNotFoundException
     */
    public function __invoke(int $portfolioId): Portfolio
    {
        $portfolio = $this->portfolioRepository->search($portfolioId);

        if (null === $portfolio) {
            throw new PortfolioNotFoundException($portfolioId);
        }

        return $portfolio;
    }
}
