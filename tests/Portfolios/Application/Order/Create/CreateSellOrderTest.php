<?php

declare(strict_types=1);

namespace App\Tests\Portfolios\Application\Order\Create;

use App\Portfolios\Application\Allocation\Find\FindAllocation;
use App\Portfolios\Application\Order\Create\CreateSellOrder;
use App\Portfolios\Application\Portfolio\Find\FindPortfolio;
use App\Portfolios\Domain\Allocation\AllocationNotFoundException;
use App\Portfolios\Domain\Portfolio\PortfolioNotFoundException;
use App\Tests\Portfolios\Application\UnitTestCase;
use App\Tests\Portfolios\Domain\Order\OrderMother;
use App\Tests\Portfolios\Domain\Portfolio\PortfolioMother;

final class CreateSellOrderTest extends UnitTestCase
{
    private CreateSellOrder $createSellOrder;

    protected function setUp(): void
    {
        $findPortfolio = new FindPortfolio($this->portfolioRepository());
        $findAllocation = new FindAllocation($this->allocationRepository());

        $this->createSellOrder = new CreateSellOrder(
            $findPortfolio,
            $findAllocation,
            $this->orderRepository()
        );
    }

    /**
     * @throws PortfolioNotFoundException
     */
    public function testItShouldCreateASellOrder(): void
    {
        $portfolio = PortfolioMother::withAllocations();

        $allocation = $portfolio->getAllocations()->first();

        $order = OrderMother::typeSell($portfolio);

        $createOrderRequest = CreateOrderRequestMother::fromOrder($order);

        $this->portfolioRepository()
            ->expects($this->once())
            ->method('search')
            ->with($createOrderRequest->getPortfolioId())
            ->willReturn($portfolio);

        $this->allocationRepository()
            ->expects($this->once())
            ->method('search')
            ->with($createOrderRequest->getAllocationId())
            ->willReturn($allocation);

        $this->orderRepository()
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $this->createSellOrder->__invoke($createOrderRequest);
    }

    public function testItShouldNotCreateASellOrderWithNonExistingPortfolio(): void
    {
        $this->expectException(PortfolioNotFoundException::class);

        $createOrderRequest = CreateOrderRequestMother::create();

        $this->portfolioRepository()
            ->expects($this->once())
            ->method('search')
            ->with($createOrderRequest->getPortfolioId())
            ->willReturn(null);

        $this->allocationRepository()
            ->expects($this->never())
            ->method('search');

        $this->orderRepository()
            ->expects($this->never())
            ->method('save');

        $this->createSellOrder->__invoke($createOrderRequest);
    }

    /**
     * @throws PortfolioNotFoundException
     */
    public function testItShouldNotCreateASellOrderWithNonExistingAllocation(): void
    {
        $this->expectException(AllocationNotFoundException::class);

        $portfolio = PortfolioMother::withAllocations();

        $createOrderRequest = CreateOrderRequestMother::create();

        $this->portfolioRepository()
            ->expects($this->once())
            ->method('search')
            ->with($createOrderRequest->getPortfolioId())
            ->willReturn($portfolio);

        $this->allocationRepository()
            ->expects($this->once())
            ->method('search')
            ->with($createOrderRequest->getAllocationId())
            ->willReturn(null);

        $this->orderRepository()
            ->expects($this->never())
            ->method('save');

        $this->createSellOrder->__invoke($createOrderRequest);
    }
}
