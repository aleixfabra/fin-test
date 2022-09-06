<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Create;

final class CreatePortfolioRequest
{
    private int $portfolioId;
    private array $allocations;

    public function __construct(int $portfolioId, array $allocations = [])
    {
        $this->portfolioId = $portfolioId;
        $this->allocations = $allocations;
    }

    public function getPortfolioId(): int
    {
        return $this->portfolioId;
    }

    public function getAllocations(): array
    {
        return $this->allocations;
    }
}
