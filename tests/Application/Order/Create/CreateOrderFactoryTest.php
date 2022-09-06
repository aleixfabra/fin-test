<?php

declare(strict_types=1);

namespace App\Tests\Application\Order\Create;

use App\Application\Allocation\Find\FindAllocation;
use App\Application\Order\Create\CreateBuyOrder;
use App\Application\Order\Create\CreateOrderFactory;
use App\Application\Order\Create\CreateSellOrder;
use App\Application\Portfolio\Find\FindPortfolio;
use App\Domain\Order\Order;
use App\Tests\Application\UnitTestCase;
use InvalidArgumentException;

final class CreateOrderFactoryTest extends UnitTestCase
{
    private CreateOrderFactory $createOrderFactory;

    protected function setUp(): void
    {
        $findPortfolio = new FindPortfolio($this->portfolioRepository());
        $findAllocation = new FindAllocation($this->allocationRepository());

        $createBuyOrder = new CreateBuyOrder(
            $findPortfolio,
            $this->orderRepository()
        );

        $createSellOrder = new CreateSellOrder(
            $findPortfolio,
            $findAllocation,
            $this->orderRepository()
        );

        $this->createOrderFactory = new CreateOrderFactory(
            $createBuyOrder,
            $createSellOrder
        );
    }

    public function testItShouldReturnACreateBuyOrder(): void
    {
        $createBuyOrder = $this->createOrderFactory->__invoke(Order::TYPE_BUY);

        $this->assertInstanceOf(CreateBuyOrder::class, $createBuyOrder);
    }

    public function testItShouldReturnACreateSellOrder(): void
    {
        $createSellOrder = $this->createOrderFactory->__invoke(Order::TYPE_SELL);

        $this->assertInstanceOf(CreateSellOrder::class, $createSellOrder);
    }

    public function testItShouldNotReturnACreateOrder(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $type = 'not_existing_type';

        $this->createOrderFactory->__invoke($type);
    }
}
