<?php

declare(strict_types=1);

namespace ItaliaMultimedia\GestPay\Service;

use Fig\Http\Message\RequestMethodInterface;
use ItaliaMultimedia\GestPay\Contract\ApiClientServiceInterface;
use ItaliaMultimedia\GestPay\Contract\AxervePaymentServiceInterface;
use ItaliaMultimedia\GestPay\Contract\EnvironmentInterface;
use ItaliaMultimedia\GestPay\Contract\UrlInterface;
use ItaliaMultimedia\GestPay\DataTransfer\AuthenticationOptions;
use ItaliaMultimedia\GestPay\Exceptions\GestPayException;
use OutOfRangeException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;

use function array_key_exists;
use function in_array;
use function is_array;
use function json_encode;
use function sprintf;

abstract class AbstractPaymentService implements AxervePaymentServiceInterface
{
    protected ?string $apiKey = null;
    protected ?string $paymentToken = null;

    public function __construct(
        protected ApiClientServiceInterface $apiClientService,
        protected string $currency,
        protected string $environment,
        protected LoggerInterface $logger,
        protected string $shopLogin,
    ) {
    }

    public function setApiKey(string $apiKey): bool
    {
        $this->apiKey = $apiKey;

        return true;
    }

    public function setPaymentToken(string $paymentToken): bool
    {
        $this->paymentToken = $paymentToken;

        return true;
    }

    /**
     * @param array<string,string|array<string,string>> $data
     */
    protected function createRequestBody(array $data): string
    {
        $body = json_encode($data);

        if ($body === false) {
            throw new UnexpectedValueException('Error encoding data to JSON.');
        }

        return $body;
    }

    /**
     * @param array<string,string|array<string,string>> $data
     */
    protected function createPostRequest(array $data, string $endpoint): RequestInterface
    {
        return $this->apiClientService->createRequest(
            $this->getAuthenticationOptions(),
            $this->createRequestBody($data),
            RequestMethodInterface::METHOD_POST,
            $this->getApiUrl($endpoint),
        );
    }

    protected function getAuthenticationOptions(): AuthenticationOptions
    {
        return new AuthenticationOptions($this->apiKey, $this->paymentToken);
    }

    protected function getApiBaseUrl(): string
    {
        switch ($this->environment) {
            case EnvironmentInterface::PRODUCTION:
                return UrlInterface::PRODUCTION;
            case EnvironmentInterface::SANDBOX:
                return UrlInterface::SANDBOX;
            default:
                throw new UnexpectedValueException('Unhandled environment value.');
        }
    }

    protected function getApiUrl(string $endpoint): string
    {
        return sprintf('%s%s', $this->getApiBaseUrl(), $endpoint);
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $responseBody
     */
    protected function getResponseErrorCode(array $responseBody): int
    {
        if (array_key_exists('error', $responseBody)) {
            if (is_array($responseBody['error']) && array_key_exists('code', $responseBody['error'])) {
                return (int) $responseBody['error']['code'];
            }
        }

        return 0;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $responseBody
     */
    protected function getResponseErrorMessage(array $responseBody): string
    {
        if (array_key_exists('error', $responseBody)) {
            if (is_array($responseBody['error']) && array_key_exists('description', $responseBody['error'])) {
                return (string) $responseBody['error']['description'];
            }
        }

        return 'Unspecified GestPay API Error';
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     */
    protected function getResponsePayload(ResponseInterface $response): array
    {
        $responseBodyAsArray = $this->apiClientService->getResponseBodyAsArray($response);

        if ($response->getStatusCode() === 200) {
            if (!array_key_exists('payload', $responseBodyAsArray)) {
                throw new UnexpectedValueException('Missing payload.');
            }
            if (!is_array($responseBodyAsArray['payload'])) {
                throw new OutOfRangeException('Payload is not an array');
            }

            return $responseBodyAsArray['payload'];
        }

        throw new GestPayException(
            $this->getResponseErrorMessage($responseBodyAsArray),
            $this->getResponseErrorCode($responseBodyAsArray),
        );
    }

    protected function logRequest(RequestInterface $request): bool
    {
        $this->logger->debug(
            'Request debug (context).',
            [
                'request_body' => $request->getBody()->getContents(),
                'request_headers' => $request->getHeaders(),
                'request_method' => $request->getMethod(),
                'request_uri' => $request->getUri()->__toString(),
            ],
        );

        // Important! Otherwise the stream body contents can not be retrieved later.
        $request->getBody()->rewind();

        return true;
    }

    protected function logResponse(ResponseInterface $response): bool
    {
        $this->logger->debug(
            'Response debug (context).',
            [
                'response_body' => $response->getBody()->getContents(),
                'response_headers' => $response->getHeaders(),
                'response_reason_phrase' => $response->getReasonPhrase(),
                'response_status' => $response->getStatusCode(),
            ],
        );

        // Important! Otherwise the stream body contents can not be retrieved later.
        $response->getBody()->rewind();

        return true;
    }

    protected function validateItemType(string $itemType): bool
    {
        if (in_array($itemType, ['digital', 'physical'], true)) {
            return true;
        }

        throw new UnexpectedValueException('Invalid itemType');
    }
}
