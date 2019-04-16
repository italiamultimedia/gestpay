<?php
namespace ItaliaMultimedia\GestPay\Exceptions;

class GestPayException extends \WebServCo\Framework\Exceptions\ApplicationException
{
    const CODE = 0;

    public function __construct($message, $code = self::CODE, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
