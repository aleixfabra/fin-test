<?php

declare(strict_types=1);

namespace App\Portfolios\Domain\Allocation;

use App\Shared\Domain\NotFoundException;

final class AllocationNotFoundException extends NotFoundException
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;

        parent::__construct($this->errorMessage());
    }

    protected function errorMessage(): string
    {
        return sprintf('The Allocation #%s does not exist', $this->id);
    }
}
