<?php

declare(strict_types=1);

namespace App\Portfolios\Domain\Order;

use App\Portfolios\Domain\Portfolio\Portfolio;
use App\Shared\Domain\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="portfolio_order")
 */
class Order implements Entity
{
    public const TYPE_BUY = 'buy';
    public const TYPE_SELL = 'sell';

    public const TYPES_ALLOWED = [
        self::TYPE_BUY,
        self::TYPE_SELL,
    ];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Portfolios\Domain\Portfolio\Portfolio", inversedBy="orders")
     */
    private Portfolio $portfolio;

    /**
     * @ORM\Column(type="integer")
     */
    private int $allocationId;

    /**
     * @ORM\Column(type="integer")
     */
    private int $shares;

    /**
     * @ORM\Column(type="string")
     */
    private string $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isCompleted;

    public function __construct(
        int $id,
        Portfolio $portfolio,
        int $allocationId,
        int $shares,
        string $type
    ) {
        $this->id = $id;
        $this->portfolio = $portfolio;
        $this->allocationId = $allocationId;
        $this->shares = $shares;
        $this->type = $type;
        $this->isCompleted = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPortfolio(): Portfolio
    {
        return $this->portfolio;
    }

    public function getAllocationId(): int
    {
        return $this->allocationId;
    }

    public function getShares(): int
    {
        return $this->shares;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isTypeBuy(): bool
    {
        return self::TYPE_BUY === $this->type;
    }

    public function isTypeSell(): bool
    {
        return self::TYPE_SELL === $this->type;
    }

    public function completed(): void
    {
        $this->isCompleted = true;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function isTypeBuyAndNotCompleted(): bool
    {
        return $this->isTypeBuy() && !$this->isCompleted();
    }

    public function isTypeSellAndNotCompleted(): bool
    {
        return $this->isTypeSell() && !$this->isCompleted();
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'allocationId' => $this->allocationId,
            'shares' => $this->shares,
            'type' => $this->type,
            'portfolio' => [
                'id' => $this->getPortfolio()->getId(),
            ],
        ];
    }
}
