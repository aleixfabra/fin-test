<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Create;

use App\Domain\Allocation\Allocation;
use App\Domain\Portfolio\Portfolio;
use App\Domain\Portfolio\PortfolioRepository;

final class CreatePortfolio
{
    private PortfolioRepository $portfolioRepository;

    public function __construct(PortfolioRepository $portfolioRepository)
    {
        $this->portfolioRepository = $portfolioRepository;
    }

    public function __invoke(CreatePortfolioRequest $createPortfolioRequest): void
    {
        $portfolioId = $createPortfolioRequest->getPortfolioId();

        $portfolio = new Portfolio($portfolioId);

        $allocations = self::buildAllocations($portfolio, $createPortfolioRequest->getAllocations());
        $portfolio->addAllocations($allocations);

        $this->portfolioRepository->save($portfolio);
    }

    /**
     * @return Allocation[]
     */
    private static function buildAllocations(Portfolio $portfolio, array $allocationsPayload): array
    {
        return array_map(function (array $allocation) use ($portfolio) {
            return new Allocation($allocation['id'], $allocation['shares'], $portfolio);
        }, $allocationsPayload);
    }
}
