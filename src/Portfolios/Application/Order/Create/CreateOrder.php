<?php

declare(strict_types=1);

namespace App\Portfolios\Application\Order\Create;

use App\Portfolios\Application\Portfolio\Find\FindPortfolio;
use App\Portfolios\Domain\Order\Order;
use App\Portfolios\Domain\Order\OrderRepository;
use App\Portfolios\Domain\Portfolio\PortfolioNotFoundException;

abstract class CreateOrder
{
    private FindPortfolio $findPortfolio;

    private OrderRepository $orderRepository;

    public function __construct(
        FindPortfolio $findPortfolio,
        OrderRepository $orderRepository
    ) {
        $this->findPortfolio = $findPortfolio;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @throws PortfolioNotFoundException
     */
    public function __invoke(CreateOrderRequest $createOrderRequest): void
    {
        $portfolio = $this->findPortfolio->__invoke($createOrderRequest->getPortfolioId());

        $this->ensuresCreateOrderCouldBeExecuted($createOrderRequest);

        $order = new Order(
            $createOrderRequest->getOrderId(),
            $portfolio,
            $createOrderRequest->getAllocationId(),
            $createOrderRequest->getShares(),
            $createOrderRequest->getType()
        );

        $this->orderRepository->save($order);
    }

    protected function ensuresCreateOrderCouldBeExecuted(CreateOrderRequest $createOrderRequest): void
    {
    }
}
