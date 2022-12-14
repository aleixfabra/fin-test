<?php

declare(strict_types=1);

namespace App\Tests\Portfolios\Application\Order\Create;

use App\Portfolios\Application\Order\Create\CreateBuyOrder;
use App\Portfolios\Application\Portfolio\Find\FindPortfolio;
use App\Portfolios\Domain\Portfolio\PortfolioNotFoundException;
use App\Tests\Portfolios\Application\UnitTestCase;
use App\Tests\Portfolios\Domain\Order\OrderMother;
use App\Tests\Portfolios\Domain\Portfolio\PortfolioMother;

final class CreateBuyOrderTest extends UnitTestCase
{
    private CreateBuyOrder $createBuyOrder;

    protected function setUp(): void
    {
        $findPortfolio = new FindPortfolio($this->portfolioRepository());

        $this->createBuyOrder = new CreateBuyOrder(
            $findPortfolio,
            $this->orderRepository()
        );
    }

    /**
     * @throws PortfolioNotFoundException
     */
    public function testItShouldCreateABuyOrder(): void
    {
        $portfolio = PortfolioMother::create();

        $order = OrderMother::typeBuy($portfolio);

        $createOrderRequest = CreateOrderRequestMother::fromOrder($order);

        $this->portfolioRepository()
            ->expects($this->once())
            ->method('search')
            ->with($createOrderRequest->getPortfolioId())
            ->willReturn($portfolio);

        $this->orderRepository()
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $this->createBuyOrder->__invoke($createOrderRequest);
    }

    public function testItShouldNotCreateABuyOrderWithNonExistingPortfolio(): void
    {
        $this->expectException(PortfolioNotFoundException::class);

        $createOrderRequest = CreateOrderRequestMother::create();

        $this->portfolioRepository()
            ->expects($this->once())
            ->method('search')
            ->with($createOrderRequest->getPortfolioId())
            ->willReturn(null);

        $this->orderRepository()
            ->expects($this->never())
            ->method('save');

        $this->createBuyOrder->__invoke($createOrderRequest);
    }
}
