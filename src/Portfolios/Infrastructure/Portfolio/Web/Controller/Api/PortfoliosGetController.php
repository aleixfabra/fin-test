<?php

declare(strict_types=1);

namespace App\Portfolios\Infrastructure\Portfolio\Web\Controller\Api;

use App\Portfolios\Application\Portfolio\Find\FindPortfolio;
use App\Portfolios\Domain\Allocation\Allocation;
use App\Portfolios\Domain\Portfolio\Portfolio;
use App\Shared\Domain\NotFoundException;
use App\Shared\Infrastructure\Web\Controller\Api\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/portfolios/{id}", methods={"GET"})
 */
final class PortfoliosGetController extends ApiController
{
    private FindPortfolio $findPortfolio;

    public function __construct(FindPortfolio $findPortfolio)
    {
        $this->findPortfolio = $findPortfolio;
    }

    public function __invoke(int $id): Response
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
            'allocations' => self::buildAllocationsResponseData($portfolio),
        ];
    }

    private static function buildAllocationsResponseData(Portfolio $portfolio): array
    {
        return array_map(function (Allocation $allocation) {
            return [
                'id' => $allocation->getId(),
                'shares' => $allocation->getShares(),
            ];
        }, $portfolio->getAllocations()->getValues());
    }
}
