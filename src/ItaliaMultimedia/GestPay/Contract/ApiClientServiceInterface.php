<?php

declare(strict_types=1);

namespace ItaliaMultimedia\GestPay\Contract;

use ItaliaMultimedia\GestPay\DataTransfer\AuthenticationOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ApiClientServiceInterface
{
    public function createRequest(
        AuthenticationOptions $authenticationOptions,
        ?string $body,
        string $method,
        string $uri,
    ): RequestInterface;

    public function getResponse(RequestInterface $request): ResponseInterface;

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     */
    public function getResponseBodyAsArray(ResponseInterface $response): array;
}
