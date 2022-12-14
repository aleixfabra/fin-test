<?php

declare(strict_types=1);

namespace App\Tests\Portfolios\Infrastructure\Portfolio\Persistence;

use App\Portfolios\Domain\Portfolio\PortfolioRepository;
use App\Tests\Portfolios\Domain\Portfolio\PortfolioMother;
use App\Tests\Portfolios\Infrastructure\InfrastructureTestCase;

final class PortfolioRepositoryTest extends InfrastructureTestCase
{
    private PortfolioRepository $portfolioRepository;

    protected function setUp(): void
    {
        $this->portfolioRepository = $this->service(PortfolioRepository::class);
    }

    public function testItShouldCreateAPortfolio(): void
    {
        $portfolio = PortfolioMother::withAllocations();

        $this->portfolioRepository->save($portfolio);
    }

    public function testItShouldFindAPortfolio(): void
    {
        $expectedPortfolio = PortfolioMother::create();

        $this->portfolioRepository->save($expectedPortfolio);
        $this->clearUnitOfWork();

        $actualPortfolio = $this->portfolioRepository->search($expectedPortfolio->getId());

        $this->assertSame(
            $expectedPortfolio->toPrimitives(),
            $actualPortfolio->toPrimitives()
        );
    }

    public function testItShouldNotFindAPortfolio(): void
    {
        $nonExistingPortfolioId = hexdec(uniqid());

        $this->assertNull(
            $this->portfolioRepository->search($nonExistingPortfolioId)
        );
    }
}
