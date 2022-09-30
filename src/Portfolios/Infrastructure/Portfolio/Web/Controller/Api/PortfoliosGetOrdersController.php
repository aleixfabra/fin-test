<?php

declare(strict_types=1);

namespace App\Portfolios\Infrastructure\Portfolio\Web\Controller\Api;

use App\Portfolios\Application\Portfolio\Find\FindPortfolio;
use App\Portfolios\Domain\Order\Order;
use App\Portfolios\Domain\Portfolio\Portfolio;
use App\Shared\Domain\NotFoundException;
use App\Shared\Infrastructure\Web\Controller\Api\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/portfolios/{id}/orders", methods={"GET"})
 */
final class PortfoliosGetOrdersController extends ApiController
{
    private FindPortfolio $findPortfolio;

    public function __construct(FindPortfolio $findPortfolio)
    {
        $this->findPortfolio = $findPortfolio;
    }

    public function __invoke(int $id, Request $request): Response
    {
        try {
            $portfolio = $this->findPortfolio->__invoke($id);

            return $this->jsonResponse(
                self::buildResponseData($portfolio)
            );
        } catch (NotFoundException $exception) {
            return $this->notFoundResponse();
        }
    }

    private static function buildResponseData(Portfolio $portfolio): array
    {
        return [
            'id' => $portfolio->getId(),
            'orders' => self::buildOrdersResponseData($portfolio),
        ];
    }

    private static function buildOrdersResponseData(Portfolio $portfolio): array
    {
        return array_map(function (Order $order) {
            return [
                'id' => $order->getId(),
                'allocation' => $order->getAllocationId(),
                'shares' => $order->getShares(),
                'type' => $order->getType(),
                'completed' => $order->isCompleted(),
            ];
        }, $portfolio->getOrders()->getValues());
    }
}
