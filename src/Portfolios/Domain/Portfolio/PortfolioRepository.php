<?php

declare(strict_types=1);

namespace App\Portfolios\Domain\Portfolio;

interface PortfolioRepository
{
    public function save(Portfolio $portfolio): void;

    public function search(int $id): ?Portfolio;
}
