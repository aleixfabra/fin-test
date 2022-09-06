<?php

declare(strict_types=1);

namespace App\Tests\Application\Order\Complete;

use App\Application\Order\Complete\CompleteBuyOrder;
use App\Application\Order\Find\FindOrder;
use App\Domain\Order\OrderNotFoundException;
use App\Tests\Application\UnitTestCase;
use App\Tests\Domain\Allocation\AllocationMother;
use App\Tests\Domain\Order\OrderMother;
use App\Tests\Domain\Portfolio\PortfolioMother;

final class CompleteBuyOrderTest extends UnitTestCase
{
    private CompleteBuyOrder $completeBuyOrder;

    protected function setUp(): void
    {
        $findOrder = new FindOrder($this->orderRepository());

        $this->completeBuyOrder = new CompleteBuyOrder(
            $findOrder,
            $this->orderRepository(),
            $this->allocationRepository()
        );
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldCompleteABuyOrderWithExistingAllocation(): void
    {
        $portfolio = PortfolioMother::create();
        $allocation = AllocationMother::withPortfolio($portfolio);
        $order = OrderMother::typeBuy($portfolio);

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($order->getId())
            ->willReturn($order);

        $this->allocationRepository()
            ->expects($this->once())
            ->method('search')
            ->with($order->getAllocationId())
            ->willReturn($allocation);

        $this->allocationRepository()
            ->expects($this->once())
            ->method('save')
            ->with($allocation);

        $this->orderRepository()
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $this->completeBuyOrder->__invoke($order->getId());
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldCompleteABuyOrderWithNonExistingAllocation(): void
    {
        $portfolio = PortfolioMother::create();
        $order = OrderMother::typeBuy($portfolio);

        $allocation = AllocationMother::withShares($portfolio, $order->getShares());

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($order->getId())
            ->willReturn($order);

        $this->allocationRepository()
            ->expects($this->once())
            ->method('search')
            ->with($order->getAllocationId())
            ->willReturn(null);

        $this->allocationRepository()
            ->expects($this->once())
            ->method('save')
            ->with($allocation);

        $this->orderRepository()
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $this->completeBuyOrder->__invoke($order->getId());
    }

    public function testItShouldNotFindAnUnknownOrder(): void
    {
        $this->expectException(OrderNotFoundException::class);

        $nonExistingOrderId = hexdec(uniqid());

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($nonExistingOrderId)
            ->willReturn(null);

        $this->allocationRepository()
            ->expects($this->never())
            ->method('search');

        $this->allocationRepository()
            ->expects($this->never())
            ->method('save');

        $this->orderRepository()
            ->expects($this->never())
            ->method('save');

        $this->completeBuyOrder->__invoke($nonExistingOrderId);
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldNotCompleteANonBuyOrder(): void
    {
        $portfolio = PortfolioMother::create();
        $order = OrderMother::typeSell($portfolio);

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($order->getId())
            ->willReturn($order);

        $this->allocationRepository()
            ->expects($this->never())
            ->method('search');

        $this->allocationRepository()
            ->expects($this->never())
            ->method('save');

        $this->orderRepository()
            ->expects($this->never())
            ->method('save');

        $this->completeBuyOrder->__invoke($order->getId());
    }
}
