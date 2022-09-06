<?php

declare(strict_types=1);

namespace App\Application\Allocation\Find;

use App\Domain\Allocation\Allocation;
use App\Domain\Allocation\AllocationNotFoundException;
use App\Domain\Allocation\AllocationRepository;

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
