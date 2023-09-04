<?php

declare(strict_types=1);

namespace ItaliaMultimedia\GestPay\Contract;

interface UrlInterface
{
    public const PRODUCTION = 'https://ecomms2s.sella.it/api/v1/';
    public const SANDBOX = 'https://sandbox.gestpay.net/api/v1/';
}
