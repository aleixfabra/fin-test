<?php

declare(strict_types=1);

namespace App\Domain\Allocation;

interface AllocationRepository
{
    public function save(Allocation $allocation): void;

    public function search(int $id): ?Allocation;

    public function delete(Allocation $allocation): void;
}
