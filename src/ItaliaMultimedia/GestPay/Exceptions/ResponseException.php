<?php
namespace ItaliaMultimedia\GestPay\Exceptions;

final class ResponseException extends GestPayException
{
    const CODE = 0;

    public function __construct($message, $code = self::CODE)
    {
        parent::__construct($message, $code);
    }
}
