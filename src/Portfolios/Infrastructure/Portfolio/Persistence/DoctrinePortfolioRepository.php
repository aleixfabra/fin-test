<?php

namespace App\Portfolios\Infrastructure\Portfolio\Persistence;

use App\Portfolios\Domain\Portfolio\Portfolio;
use App\Portfolios\Domain\Portfolio\PortfolioRepository;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;

final class DoctrinePortfolioRepository extends DoctrineRepository implements PortfolioRepository
{
    public function save(Portfolio $portfolio): void
    {
        $this->persist($portfolio);
    }

    public function search(int $id): ?Portfolio
    {
        return $this->repository(Portfolio::class)->find($id);
    }
}
