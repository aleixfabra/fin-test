<?php

declare(strict_types=1);

namespace App\Application\Order\Find;

use App\Domain\Order\Order;
use App\Domain\Order\OrderNotFoundException;
use App\Domain\Order\OrderRepository;

final class FindOrder
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @throws OrderNotFoundException
     */
    public function __invoke(int $orderId): Order
    {
        $portfolio = $this->orderRepository->search($orderId);

        if (null === $portfolio) {
            throw new OrderNotFoundException($orderId);
        }

        return $portfolio;
    }
}
