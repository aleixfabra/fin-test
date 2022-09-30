<?php

declare(strict_types=1);

namespace App\Tests\Application\Portfolio\Create;

use App\Portfolios\Application\Portfolio\Create\CreatePortfolio;
use App\Tests\Application\UnitTestCase;
use App\Tests\Domain\Portfolio\PortfolioMother;

final class CreatePortfolioTest extends UnitTestCase
{
    private CreatePortfolio $createPortfolio;

    protected function setUp(): void
    {
        $this->createPortfolio = new CreatePortfolio($this->portfolioRepository());
    }

    public function testItShouldCreateAPortfolio(): void
    {
        $portfolio = PortfolioMother::withAllocations();

        $createPortfolioRequest = CreatePortfolioRequestMother::fromPortfolio($portfolio);

        $this->portfolioRepository()
            ->expects($this->once())
            ->method('save')
            ->with($portfolio);

        $this->createPortfolio->__invoke($createPortfolioRequest);
    }
}
