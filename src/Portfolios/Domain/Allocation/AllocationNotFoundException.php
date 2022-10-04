<?php

declare(strict_types=1);

namespace App\Portfolios\Domain\Allocation;

use App\Shared\Domain\NotFoundException;

final class AllocationNotFoundException extends NotFoundException
{
    protected function errorMessage(): string
    {
        return sprintf('The Allocation #%s does not exist', $this->id);
    }
}
