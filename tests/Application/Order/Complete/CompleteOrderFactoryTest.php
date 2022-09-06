<?php

declare(strict_types=1);

namespace App\Tests\Application\Order\Complete;

use App\Application\Allocation\Find\FindAllocation;
use App\Application\Order\Complete\CompleteBuyOrder;
use App\Application\Order\Complete\CompleteOrderFactory;
use App\Application\Order\Complete\CompleteSellOrder;
use App\Application\Order\Find\FindOrder;
use App\Domain\Order\OrderNotFoundException;
use App\Tests\Application\UnitTestCase;
use App\Tests\Domain\Order\OrderMother;
use App\Tests\Domain\Portfolio\PortfolioMother;
use InvalidArgumentException;

final class CompleteOrderFactoryTest extends UnitTestCase
{
    private CompleteOrderFactory $completeOrderFactory;

    protected function setUp(): void
    {
        $findOrder = new FindOrder($this->orderRepository());
        $findAllocation = new FindAllocation($this->allocationRepository());

        $completeBuyOrder = new CompleteBuyOrder(
            $findOrder,
            $this->orderRepository(),
            $this->allocationRepository()
        );

        $completeSellOrder = new CompleteSellOrder(
            $findOrder,
            $findAllocation,
            $this->orderRepository(),
            $this->allocationRepository()
        );

        $this->completeOrderFactory = new CompleteOrderFactory(
            $findOrder,
            $completeBuyOrder,
            $completeSellOrder
        );
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldReturnACompleteBuyOrder(): void
    {
        $portfolio = PortfolioMother::create();
        $order = OrderMother::typeBuy($portfolio);

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($order->getId())
            ->willReturn($order);

        $completeBuyOrder = $this->completeOrderFactory->__invoke($order->getId());

        $this->assertInstanceOf(CompleteBuyOrder::class, $completeBuyOrder);
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldReturnACompleteSellOrder(): void
    {
        $portfolio = PortfolioMother::create();
        $order = OrderMother::typeSell($portfolio);

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($order->getId())
            ->willReturn($order);

        $completeSellOrder = $this->completeOrderFactory->__invoke($order->getId());

        $this->assertInstanceOf(CompleteSellOrder::class, $completeSellOrder);
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldNotReturnACompleteOrderOfUnknownOrderType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $order = OrderMother::unknownType();

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($order->getId())
            ->willReturn($order);

        $this->completeOrderFactory->__invoke($order->getId());
    }

    public function testItShouldNotReturnACompleteOrderOfUnknownOrder(): void
    {
        $this->expectException(OrderNotFoundException::class);

        $nonExistingOrderId = hexdec(uniqid());

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($nonExistingOrderId)
            ->willReturn(null);

        $this->completeOrderFactory->__invoke($nonExistingOrderId);
    }
}
