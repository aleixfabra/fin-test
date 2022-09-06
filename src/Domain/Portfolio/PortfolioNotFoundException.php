<?php

declare(strict_types=1);

namespace App\Domain\Portfolio;

use App\Shared\Domain\NotFoundException;

final class PortfolioNotFoundException extends NotFoundException
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;

        parent::__construct($this->errorMessage());
    }

    protected function errorMessage(): string
    {
        return sprintf('The Portfolio #%s does not exist', $this->id);
    }
}
