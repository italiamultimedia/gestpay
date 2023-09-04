<?php

declare(strict_types=1);

namespace ItaliaMultimedia\GestPay\Contract;

interface AxervePaymentServiceInterface
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
    ): array;

    /**
     * @param array<string,string|array<string,string>> $extraData
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     */
    public function getPaymentDetails(array $extraData = []): array;

    /**
     * @param array<string,string|array<string,string>> $extraData
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @return array<mixed>
     */
    public function submitPayment(array $extraData = []): array;

    public function setApiKey(string $apiKey): bool;

    public function setPaymentToken(string $paymentToken): bool;
}
