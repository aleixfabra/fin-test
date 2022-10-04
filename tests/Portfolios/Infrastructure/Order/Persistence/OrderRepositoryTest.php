<?php

declare(strict_types=1);

namespace App\Tests\Portfolios\Infrastructure\Order\Persistence;

use App\Portfolios\Domain\Order\OrderRepository;
use App\Portfolios\Domain\Portfolio\PortfolioRepository;
use App\Tests\Portfolios\Domain\Order\OrderMother;
use App\Tests\Portfolios\Domain\Portfolio\PortfolioMother;
use App\Tests\Portfolios\Infrastructure\InfrastructureTestCase;

final class OrderRepositoryTest extends InfrastructureTestCase
{
    private PortfolioRepository $portfolioRepository;

    private OrderRepository $orderRepository;

    protected function setUp(): void
    {
        $this->portfolioRepository = $this->service(PortfolioRepository::class);
        $this->orderRepository = $this->service(OrderRepository::class);
    }

    public function testItShouldCreateAnOrder(): void
    {
        $portfolio = PortfolioMother::create();
        $this->portfolioRepository->save($portfolio);

        $order = OrderMother::withPortfolio($portfolio);
        $this->orderRepository->save($order);
    }

    public function testItShouldFindAnOrder(): void
    {
        $portfolio = PortfolioMother::create();
        $this->portfolioRepository->save($portfolio);

        $expectedOrder = OrderMother::withPortfolio($portfolio);
        $this->orderRepository->save($expectedOrder);

        $actualOrder = $this->orderRepository->search($expectedOrder->getId());

        $this->assertSame(
            $expectedOrder->toPrimitives(),
            $actualOrder->toPrimitives()
        );
    }

    public function testItShouldNotFindAnOrder(): void
    {
        $nonExistingOrderId = hexdec(uniqid());

        $this->assertNull(
            $this->orderRepository->search($nonExistingOrderId)
        );
    }
}
