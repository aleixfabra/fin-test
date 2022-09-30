<?php

declare(strict_types=1);

namespace App\Tests\Application\Portfolio\Find;

use App\Portfolios\Application\Portfolio\Find\FindPortfolio;
use App\Portfolios\Domain\Portfolio\PortfolioNotFoundException;
use App\Tests\Application\UnitTestCase;
use App\Tests\Domain\Portfolio\PortfolioMother;

final class FindPortfolioTest extends UnitTestCase
{
    private FindPortfolio $findPortfolio;

    protected function setUp(): void
    {
        $this->findPortfolio = new FindPortfolio($this->portfolioRepository());
    }

    /**
     * @throws PortfolioNotFoundException
     */
    public function testItShouldFindAPortfolio(): void
    {
        $expectedPortfolio = PortfolioMother::create();

        $this->portfolioRepository()
            ->expects($this->once())
            ->method('search')
            ->with($expectedPortfolio->getId())
            ->willReturn($expectedPortfolio);

        $actualPortfolio = $this->findPortfolio->__invoke($expectedPortfolio->getId());

        $this->assertSame($expectedPortfolio, $actualPortfolio);
    }

    public function testItShouldNotFindAPortfolio(): void
    {
        $this->expectException(PortfolioNotFoundException::class);

        $nonExistingPortfolioId = hexdec(uniqid());

        $this->portfolioRepository()
            ->expects($this->once())
            ->method('search')
            ->with($nonExistingPortfolioId)
            ->willReturn(null);

        $this->findPortfolio->__invoke($nonExistingPortfolioId);
    }
}
