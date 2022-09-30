<?php

declare(strict_types=1);

namespace App\Portfolios\Application\Order\Create;

final class CreateOrderRequest
{
    private int $orderId;

    private int $portfolioId;

    private int $allocationId;

    private int $shares;

    private string $type;

    public function __construct(
        int $orderId,
        int $portfolioId,
        int $allocationId,
        int $shares,
        string $type
    ) {
        $this->orderId = $orderId;
        $this->portfolioId = $portfolioId;
        $this->allocationId = $allocationId;
        $this->shares = $shares;
        $this->type = $type;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getPortfolioId(): int
    {
        return $this->portfolioId;
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
}
