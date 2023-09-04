<?php

declare(strict_types=1);

namespace ItaliaMultimedia\GestPay\Service;

use ItaliaMultimedia\GestPay\Contract\ApiClientServiceInterface;
use ItaliaMultimedia\GestPay\DataTransfer\AuthenticationOptions;
use OutOfRangeException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use UnexpectedValueException;

use function is_array;
use function json_decode;
use function sprintf;

final class ApiClientService implements ApiClientServiceInterface
{
    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
    ) {
    }

    public function createRequest(
        AuthenticationOptions $authenticationOptions,
        ?string $body,
        string $method,
        string $uri,
    ): RequestInterface {
        $request = $this->requestFactory->createRequest($method, $uri)
        ->withHeader('Content-Type', 'application/json');

        if ($body !== null) {
            $request = $request->withBody($this->streamFactory->createStream($body));
        }

        return $this->getRequestWithAuthenticationHeader($authenticationOptions, $request);
    }

    public function getResponse(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     */
    public function getResponseBodyAsArray(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();
        if ($body === '') {
            // Possible situation: the body contents were read elsewhere and the stream was not rewinded.
            throw new UnexpectedValueException('Response body is empty.');
        }

        $array = json_decode($body, true);
        if (!is_array($array)) {
            throw new UnexpectedValueException('Error decoding JSON data.');
        }

        return $array;
    }

    private function getRequestWithAuthenticationHeader(
        AuthenticationOptions $authenticationOptions,
        RequestInterface $request,
    ): RequestInterface {
        if ($authenticationOptions->paymentToken !== null) {
            /**
             * Phan error (false positive, we are inside a non null check)
             * PhanTypeMismatchArgumentNullable Argument 2 ($value) is $authenticationOptions->paymentToken
             * of type ?string but \Psr\Http\Message\RequestInterface::withHeader() takes string|string[] defined at
             * vendor/psr/http-message/src/MessageInterface.php:132 (expected type to be non-nullable)
             *
             * @todo study/handle
             */
            return $request->withHeader('paymentToken', $authenticationOptions->paymentToken);
        }

        // If paymentToken is not present, apiKey is required.
        if ($authenticationOptions->apiKey === null) {
            throw new OutOfRangeException('Api key not set.');
        }

        /**
         * Phan error (false positive, we are after a non null check)
         * PhanTypeMismatchArgumentNullableInternal Argument 2 ($values) is $authenticationOptions->apiKey
         * of type ?string but \sprintf() takes \Stringable|float|int|string (expected type to be non-nullable)
         *
         * @todo study/handle
         */
        return $request->withHeader('Authorization', sprintf('apikey %s', $authenticationOptions->apiKey));
    }
}
