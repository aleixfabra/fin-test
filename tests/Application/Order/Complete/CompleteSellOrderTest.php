<?php

declare(strict_types=1);

namespace App\Tests\Application\Order\Complete;

use App\Application\Allocation\Find\FindAllocation;
use App\Application\Order\Complete\CompleteBuyOrder;
use App\Application\Order\Complete\CompleteSellOrder;
use App\Application\Order\Find\FindOrder;
use App\Domain\Allocation\AllocationNotFoundException;
use App\Domain\Allocation\SellOrderExceededAllocationSharesException;
use App\Domain\Order\OrderNotFoundException;
use App\Tests\Application\UnitTestCase;
use App\Tests\Domain\Allocation\AllocationMother;
use App\Tests\Domain\Order\OrderMother;
use App\Tests\Domain\Portfolio\PortfolioMother;

final class CompleteSellOrderTest extends UnitTestCase
{
    private CompleteSellOrder $completeSellOrder;

    protected function setUp(): void
    {
        $findOrder = new FindOrder($this->orderRepository());
        $findAllocation = new FindAllocation($this->allocationRepository());

        $this->completeSellOrder = new CompleteSellOrder(
            $findOrder,
            $findAllocation,
            $this->orderRepository(),
            $this->allocationRepository()
        );
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldCompleteASellOrderWithFewerSharesThanAllocation(): void
    {
        $portfolio = PortfolioMother::create();
        $order = OrderMother::typeSell($portfolio);
        $allocation = AllocationMother::withShares($portfolio, $order->getShares() + 1);

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
            ->expects($this->never())
            ->method('delete');

        $this->allocationRepository()
            ->expects($this->once())
            ->method('save')
            ->with($allocation);

        $this->orderRepository()
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $this->completeSellOrder->__invoke($order->getId());
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldCompleteASellOrderWithEqualSharesThanAllocation(): void
    {
        $portfolio = PortfolioMother::create();
        $order = OrderMother::typeSell($portfolio);
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
            ->willReturn($allocation);

        $this->allocationRepository()
            ->expects($this->once())
            ->method('delete')
            ->with($allocation);

        $this->allocationRepository()
            ->expects($this->never())
            ->method('save');

        $this->orderRepository()
            ->expects($this->once())
            ->method('save')
            ->with($order);

        $this->completeSellOrder->__invoke($order->getId());
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldCompleteASellOrderWithMoreSharesThanAllocation(): void
    {
        $this->expectException(SellOrderExceededAllocationSharesException::class);

        $portfolio = PortfolioMother::create();
        $order = OrderMother::typeSell($portfolio);
        $allocation = AllocationMother::withShares($portfolio, $order->getShares() - 1);

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
            ->expects($this->never())
            ->method('delete');

        $this->allocationRepository()
            ->expects($this->never())
            ->method('save');

        $this->orderRepository()
            ->expects($this->never())
            ->method('save');

        $this->completeSellOrder->__invoke($order->getId());
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
            ->method('delete');

        $this->allocationRepository()
            ->expects($this->never())
            ->method('save');

        $this->orderRepository()
            ->expects($this->never())
            ->method('save');

        $this->completeSellOrder->__invoke($nonExistingOrderId);
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldNotCompleteANonSellOrder(): void
    {
        $portfolio = PortfolioMother::create();
        $order = OrderMother::typeBuy($portfolio);

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
            ->method('delete');

        $this->allocationRepository()
            ->expects($this->never())
            ->method('save');

        $this->orderRepository()
            ->expects($this->never())
            ->method('save');

        $this->completeSellOrder->__invoke($order->getId());
    }

    public function testItShouldNotCompleteASellOrderWithNonExistingAllocation(): void
    {
        $this->expectException(AllocationNotFoundException::class);

        $portfolio = PortfolioMother::create();
        $order = OrderMother::typeSell($portfolio);

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
            ->expects($this->never())
            ->method('delete');

        $this->allocationRepository()
            ->expects($this->never())
            ->method('save');

        $this->orderRepository()
            ->expects($this->never())
            ->method('save');

        $this->completeSellOrder->__invoke($order->getId());
    }
}
