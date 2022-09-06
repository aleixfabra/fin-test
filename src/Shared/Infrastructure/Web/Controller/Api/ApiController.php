<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Web\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController
{
    protected function emptyResponse(): Response
    {
        return new Response();
    }

    protected function createdResponse(string $content = ''): Response
    {
        return new Response($content, Response::HTTP_CREATED);
    }

    protected function badRequestResponse(string $content = ''): Response
    {
        return new Response($content, Response::HTTP_BAD_REQUEST);
    }

    protected function notFoundResponse(string $content = ''): Response
    {
        return new Response($content, Response::HTTP_NOT_FOUND);
    }

    protected function internalServerErrorResponse(string $content = ''): Response
    {
        return new Response($content, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param mixed $data
     */
    protected function jsonResponse($data): Response
    {
        return new JsonResponse($data);
    }
}
