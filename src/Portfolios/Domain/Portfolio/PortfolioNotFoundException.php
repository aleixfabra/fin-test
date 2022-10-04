<?php

declare(strict_types=1);

namespace App\Portfolios\Domain\Portfolio;

use App\Shared\Domain\NotFoundException;

final class PortfolioNotFoundException extends NotFoundException
{
    protected function errorMessage(): string
    {
        return sprintf('The Portfolio #%s does not exist', $this->id);
    }
}
