<?php

declare(strict_types=1);

namespace ItaliaMultimedia\GestPay\DataTransfer;

final class AuthenticationOptions
{
    public function __construct(public readonly ?string $apiKey, public readonly ?string $paymentToken)
    {
    }
}
