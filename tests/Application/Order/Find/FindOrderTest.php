<?php

declare(strict_types=1);

namespace App\Tests\Application\Order\Find;

use App\Portfolios\Application\Order\Find\FindOrder;
use App\Portfolios\Domain\Order\OrderNotFoundException;
use App\Tests\Application\UnitTestCase;
use App\Tests\Domain\Order\OrderMother;

final class FindOrderTest extends UnitTestCase
{
    private FindOrder $findOrder;

    protected function setUp(): void
    {
        $this->findOrder = new FindOrder($this->orderRepository());
    }

    /**
     * @throws OrderNotFoundException
     */
    public function testItShouldFindAnOrder(): void
    {
        $expectedOrder = OrderMother::create();

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($expectedOrder->getId())
            ->willReturn($expectedOrder);

        $actualOrder = $this->findOrder->__invoke($expectedOrder->getId());

        $this->assertSame(
            $expectedOrder->toPrimitives(),
            $actualOrder->toPrimitives()
        );
    }

    public function testItShouldNotFindAnOrder(): void
    {
        $this->expectException(OrderNotFoundException::class);

        $nonExistingOrderId = hexdec(uniqid());

        $this->orderRepository()
            ->expects($this->once())
            ->method('search')
            ->with($nonExistingOrderId)
            ->willReturn(null);

        $this->findOrder->__invoke($nonExistingOrderId);
    }
}
