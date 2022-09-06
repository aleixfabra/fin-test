<?php

namespace App\Infrastructure\Allocation\Persistence;

use App\Domain\Allocation\Allocation;
use App\Domain\Allocation\AllocationRepository;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;

final class DoctrineAllocationRepository extends DoctrineRepository implements AllocationRepository
{
    public function save(Allocation $allocation): void
    {
        $this->persist($allocation);
    }

    public function search(int $id): ?Allocation
    {
        return $this->repository(Allocation::class)->find($id);
    }

    public function delete(Allocation $allocation): void
    {
        $this->remove($allocation);
    }
}
