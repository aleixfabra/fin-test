<?php

declare(strict_types=1);

namespace App\Tests\Portfolios\Application;

use App\Portfolios\Domain\Allocation\AllocationRepository;
use App\Portfolios\Domain\Order\OrderRepository;
use App\Portfolios\Domain\Portfolio\PortfolioRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class UnitTestCase extends TestCase
{
    private PortfolioRepository $portfolioRepository;

    private OrderRepository $orderRepository;

    private AllocationRepository $allocationRepository;

    /**
     * @return PortfolioRepository|MockObject
     */
    protected function portfolioRepository(): PortfolioRepository
    {
        return $this->portfolioRepository = $this->portfolioRepository ?? $this->createMock(PortfolioRepository::class);
    }

    /**
     * @return OrderRepository|MockObject
     */
    protected function orderRepository(): OrderRepository
    {
        return $this->orderRepository = $this->orderRepository ?? $this->createMock(OrderRepository::class);
    }

    /**
     * @return AllocationRepository|MockObject
     */
    protected function allocationRepository(): AllocationRepository
    {
        return $this->allocationRepository = $this->allocationRepository ?? $this->createMock(AllocationRepository::class);
    }
}
