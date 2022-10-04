<?php

declare(strict_types=1);

namespace App\Tests\Portfolios\Application\Allocation\Find;

use App\Portfolios\Application\Allocation\Find\FindAllocation;
use App\Portfolios\Domain\Allocation\AllocationNotFoundException;
use App\Tests\Portfolios\Application\UnitTestCase;
use App\Tests\Portfolios\Domain\Allocation\AllocationMother;

final class FindAllocationTest extends UnitTestCase
{
    private FindAllocation $findAllocation;

    protected function setUp(): void
    {
        $this->findAllocation = new FindAllocation($this->allocationRepository());
    }

    /**
     * @throws AllocationNotFoundException
     */
    public function testItShouldFindAnAllocation(): void
    {
        $expectedAllocation = AllocationMother::create();

        $this->allocationRepository()
            ->expects($this->once())
            ->method('search')
            ->with($expectedAllocation->getId())
            ->willReturn($expectedAllocation);

        $actualAllocation = $this->findAllocation->__invoke($expectedAllocation->getId());

        $this->assertSame($expectedAllocation, $actualAllocation);
    }

    public function testItShouldNotFindAPortfolio(): void
    {
        $this->expectException(AllocationNotFoundException::class);

        $nonExistingAllocationId = hexdec(uniqid());

        $this->allocationRepository()
            ->expects($this->once())
            ->method('search')
            ->with($nonExistingAllocationId)
            ->willReturn(null);

        $this->findAllocation->__invoke($nonExistingAllocationId);
    }
}
