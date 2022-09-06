<?php

declare(strict_types=1);

namespace App\Tests\Application\Allocation\Find;

use App\Application\Allocation\Find\FindAllocation;
use App\Domain\Allocation\AllocationNotFoundException;
use App\Tests\Application\UnitTestCase;
use App\Tests\Domain\Allocation\AllocationMother;

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
