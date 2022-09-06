<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Web\Controller\Api;

use App\Application\Order\Complete\CompleteOrderFactory;
use App\Shared\Domain\NotFoundException;
use App\Shared\Infrastructure\Web\Controller\Api\ApiController;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders/{id}", methods={"PATCH"})
 */
final class OrdersPatchController extends ApiController
{
    private CompleteOrderFactory $completeOrderFactory;

    public function __construct(CompleteOrderFactory $completeOrderFactory)
    {
        $this->completeOrderFactory = $completeOrderFactory;
    }

    public function __invoke(int $id, Request $request): Response
    {
        $requestPayload = $request->toArray();

        try {
            self::ensuresIsValidRequestPayload($requestPayload);

            $completeOrder = $this->completeOrderFactory->__invoke($id);

            $completeOrder->__invoke($id);

            return $this->emptyResponse();
        } catch (NotFoundException $exception) {
            return $this->notFoundResponse();
        }
    }

    private static function ensuresIsValidRequestPayload(array $requestBody): void
    {
        if (
            !is_string($requestBody['status'] ?? null)
            || 'completed' !== $requestBody['status']
        ) {
            throw new InvalidArgumentException();
        }
    }
}
