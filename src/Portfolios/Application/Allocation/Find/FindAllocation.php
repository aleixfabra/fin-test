<?php

declare(strict_types=1);

namespace App\Portfolios\Application\Allocation\Find;

use App\Portfolios\Domain\Allocation\Allocation;
use App\Portfolios\Domain\Allocation\AllocationNotFoundException;
use App\Portfolios\Domain\Allocation\AllocationRepository;

final class FindAllocation
{
    private AllocationRepository $allocationRepository;

    public function __construct(AllocationRepository $allocationRepository)
    {
        $this->allocationRepository = $allocationRepository;
    }

    /**
     * @throws AllocationNotFoundException
     */
    public function __invoke(int $allocationId): Allocation
    {
        $allocation = $this->allocationRepository->search($allocationId);

        if (null === $allocation) {
            throw new AllocationNotFoundException($allocationId);
        }

        return $allocation;
    }
}
