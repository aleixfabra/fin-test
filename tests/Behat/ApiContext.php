<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

final class ApiContext implements Context
{
    private KernelInterface $kernel;

    private ?Response $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given I send a :method request to :url
     *
     * @throws Exception
     */
    public function iSendARequestTo(string $method, string $url): void
    {
        $this->response = $this->kernel->handle(Request::create($url, $method));
    }

    /**
     * @Given I send a :method request to :url with body:
     *
     * @throws Exception
     */
    public function iSendARequestToWithBody(string $method, string $url, PyStringNode $body): void
    {
        $this->response = $this->kernel->handle(Request::create($url, $method, [], [], [], ['CONTENT_TYPE' => 'application/json'], $body->getRaw()));
    }

    /**
     * @Then the response status code should be :expectedResponseStatusCode
     */
    public function theResponseStatusCodeShouldBe(int $expectedResponseStatusCode): void
    {
        if ($this->response->getStatusCode() !== $expectedResponseStatusCode) {
            throw new RuntimeException(sprintf("The response status code does not match. \n Actual: <%s>\n Expected: <%s>", $this->response->getStatusCode(), $expectedResponseStatusCode));
        }
    }

    /**
     * @Then the response should be empty
     */
    public function theResponseShouldBeEmpty(): void
    {
        if ($this->response->isEmpty()) {
            throw new RuntimeException('The response should be empty');
        }
    }

    /**
     * @Then the response body should be:
     */
    public function theResponseBodyShouldBe(string $expectedResponseBody)
    {
        $actual = self::sanitizeOutput($this->response->getContent());
        $expected = self::sanitizeOutput($expectedResponseBody);

        if ($actual !== $expected) {
            throw new RuntimeException(sprintf("The response body does not match.\n Actual: <%s>\n Expected: <%s>", $actual, $expected));
        }
    }

    /**
     * @return false|string
     */
    private static function sanitizeOutput(string $output)
    {
        return json_encode(json_decode(trim($output), true));
    }
}
