<?php

declare(strict_types=1);

namespace ItaliaMultimedia\GestPay\Exceptions;

use RuntimeException;
use Throwable;

final class GestPayException extends RuntimeException
{
    private const CODE = 0;

    public function __construct(string $message, int $code = self::CODE, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
