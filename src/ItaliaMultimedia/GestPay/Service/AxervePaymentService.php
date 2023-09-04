<?php

declare(strict_types=1);

namespace ItaliaMultimedia\GestPay\Service;

use ItaliaMultimedia\GestPay\Contract\AxervePaymentServiceInterface;

use function array_merge;

final class AxervePaymentService extends AbstractPaymentService implements AxervePaymentServiceInterface
{
    /**
     * @param array<string,string|array<string,string>> $extraData
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     */
    public function createPayment(
        float $amount,
        string $itemType,
        string $shopTransactionId,
        array $extraData = [],
    ): array {
        $this->validateItemType($itemType);

        $data = array_merge(
            [
                'amount' => (string) $amount,
                'currency' => $this->currency,
                'itemType' => $itemType,
                'shopLogin' => $this->shopLogin,
                'shopTransactionID' => $shopTransactionId,
            ],
            $extraData,
        );

        $request = $this->createPostRequest($data, 'payment/create');
        $this->logRequest($request);

        $response = $this->apiClientService->getResponse($request);
        $this->logResponse($response);

        return $this->getResponsePayload($response);
    }

    /**
     * @param array<string,string|array<string,string>> $extraData
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     */
    public function getPaymentDetails(array $extraData = []): array
    {
        $data = array_merge(
            [
                'shopLogin' => $this->shopLogin,
            ],
            $extraData,
        );

        $request = $this->createPostRequest($data, 'payment/detail');
        $this->logRequest($request);

        $response = $this->apiClientService->getResponse($request);
        $this->logResponse($response);

        return $this->getResponsePayload($response);
    }

    /**
     * @param array<string,string|array<string,string>> $extraData
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     */
    public function submitPayment(array $extraData = []): array
    {
        $data = array_merge(
            [
                'shopLogin' => $this->shopLogin,
            ],
            $extraData,
        );

        $request = $this->createPostRequest($data, 'payment/submit');
        $this->logRequest($request);

        $response = $this->apiClientService->getResponse($request);
        $this->logResponse($response);

        return $this->getResponsePayload($response);
    }
}
