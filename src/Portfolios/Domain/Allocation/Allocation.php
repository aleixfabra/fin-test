<?php

declare(strict_types=1);

namespace App\Portfolios\Domain\Allocation;

use App\Portfolios\Domain\Portfolio\Portfolio;
use App\Shared\Domain\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="portfolio_allocation")
 */
class Allocation implements Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $shares;

    /**
     * @ORM\ManyToOne(targetEntity="App\Portfolios\Domain\Portfolio\Portfolio", inversedBy="allocations")
     */
    private Portfolio $portfolio;

    public function __construct(int $id, int $shares, Portfolio $portfolio)
    {
        $this->id = $id;
        $this->shares = $shares;
        $this->portfolio = $portfolio;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getShares(): int
    {
        return $this->shares;
    }

    public function addShares(int $shares): void
    {
        $this->shares += $shares;
    }

    public function removeShares(int $shares): void
    {
        $this->shares -= $shares;
    }

    public function getPortfolio(): Portfolio
    {
        return $this->portfolio;
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'shares' => $this->shares,
            'portfolio' => [
                'id' => $this->getPortfolio()->getId(),
            ],
        ];
    }
}
