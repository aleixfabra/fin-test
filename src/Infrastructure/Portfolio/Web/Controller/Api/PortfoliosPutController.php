<?php

declare(strict_types=1);

namespace App\Infrastructure\Portfolio\Web\Controller\Api;

use App\Application\Portfolio\Create\CreatePortfolio;
use App\Application\Portfolio\Create\CreatePortfolioRequest;
use App\Shared\Infrastructure\Web\Controller\Api\ApiController;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/portfolios/{id}", methods={"PUT"})
 */
final class PortfoliosPutController extends ApiController
{
    private CreatePortfolio $createPortfolio;

    public function __construct(CreatePortfolio $createPortfolio)
    {
        $this->createPortfolio = $createPortfolio;
    }

    public function __invoke(int $id, Request $request): Response
    {
        $requestPayload = $request->toArray();

        try {
            self::ensuresIsValidRequestPayload($requestPayload);

            $this->createPortfolio->__invoke(
                new CreatePortfolioRequest(
                    $id,
                    $requestPayload['allocations']
                )
            );

            return $this->emptyResponse();
        } catch (InvalidArgumentException $exception) {
            return $this->badRequestResponse();
        }
    }

    private static function ensuresIsValidRequestPayload(array $requestPayload): void
    {
        if (
            !is_array($requestPayload['allocations'] ?? null)
        ) {
            throw new InvalidArgumentException();
        }

        self::ensuresAllocationsPayloadAreValid($requestPayload['allocations']);
    }

    private static function ensuresAllocationsPayloadAreValid(array $allocations): void
    {
        foreach ($allocations as $allocation) {
            self::ensuresAllocationPayloadIsValid($allocation);
        }
    }

    private static function ensuresAllocationPayloadIsValid($allocation): void
    {
        if (
            !is_int($allocation['id'] ?? null)
            || !is_int($allocation['shares'] ?? null)
        ) {
            throw new InvalidArgumentException();
        }
    }
}
