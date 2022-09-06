<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Allocation\Persistence;

use App\Domain\Allocation\AllocationRepository;
use App\Domain\Portfolio\PortfolioRepository;
use App\Tests\Domain\Allocation\AllocationMother;
use App\Tests\Domain\Portfolio\PortfolioMother;
use App\Tests\Infrastructure\InfrastructureTestCase;

final class AllocationRepositoryTest extends InfrastructureTestCase
{
    private PortfolioRepository $portfolioRepository;

    private AllocationRepository $allocationRepository;

    protected function setUp(): void
    {
        $this->portfolioRepository = $this->service(PortfolioRepository::class);
        $this->allocationRepository = $this->service(AllocationRepository::class);
    }

    public function testItShouldCreateAnAllocation(): void
    {
        $portfolio = PortfolioMother::create();
        $this->portfolioRepository->save($portfolio);

        $allocation = AllocationMother::withPortfolio($portfolio);
        $this->allocationRepository->save($allocation);
    }

    public function testItShouldFindAnAllocation(): void
    {
        $portfolio = PortfolioMother::withAllocations();

        $this->portfolioRepository->save($portfolio);
        $expectedAllocation = $portfolio->getAllocations()->first();
        $this->clearUnitOfWork();

        $actualAllocation = $this->allocationRepository->search($expectedAllocation->getId());

        $this->assertSame(
            $expectedAllocation->toPrimitives(),
            $actualAllocation->toPrimitives()
        );
    }

    public function testItShouldNotFindAnAllocation(): void
    {
        $nonExistingAllocationId = hexdec(uniqid());

        $this->assertNull(
            $this->allocationRepository->search($nonExistingAllocationId)
        );
    }
}
