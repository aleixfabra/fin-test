<?php

declare(strict_types=1);

namespace App\Portfolios\Infrastructure\Order\Web\Controller\Api;

use App\Portfolios\Application\Order\Create\CreateOrderFactory;
use App\Portfolios\Application\Order\Create\CreateOrderRequest;
use App\Portfolios\Domain\Allocation\SellOrderExceededAllocationSharesException;
use App\Portfolios\Domain\Order\Order;
use App\Shared\Domain\NotFoundException;
use App\Shared\Infrastructure\Web\Controller\Api\ApiController;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders", methods={"POST"})
 */
final class OrdersPostController extends ApiController
{
    private CreateOrderFactory $createOrderFactory;

    public function __construct(CreateOrderFactory $createOrderFactory)
    {
        $this->createOrderFactory = $createOrderFactory;
    }

    public function __invoke(Request $request): Response
    {
        $requestPayload = $request->toArray();

        try {
            self::ensuresIsValidRequestPayload($requestPayload);

            $createOrder = $this->createOrderFactory->__invoke($requestPayload['type']);

            $createOrder->__invoke(
                new CreateOrderRequest(
                    $requestPayload['id'],
                    $requestPayload['portfolio'],
                    $requestPayload['allocation'],
                    $requestPayload['shares'],
                    $requestPayload['type']
                )
            );

            return $this->createdResponse();
        } catch (NotFoundException $exception) {
            return $this->notFoundResponse($exception->getMessage());
        } catch (InvalidArgumentException $exception) {
            return $this->badRequestResponse();
        } catch (SellOrderExceededAllocationSharesException $exception) {
            return $this->internalServerErrorResponse();
        }
    }

    private static function ensuresIsValidRequestPayload(array $requestBody): void
    {
        if (
            !is_int($requestBody['id'] ?? null)
            || !is_int($requestBody['portfolio'] ?? null)
            || !is_int($requestBody['allocation'] ?? null)
            || !is_int($requestBody['shares'] ?? null)
            || !(
                is_string($requestBody['type'] ?? null)
                && in_array($requestBody['type'], Order::TYPES_ALLOWED)
            )
        ) {
            throw new InvalidArgumentException();
        }
    }
}
