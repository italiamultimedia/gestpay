<?php
namespace Tests\GestPay;

use PHPUnit\Framework\TestCase;
use ItaliaMultimedia\GestPay\GestPay;

final class GestPayTest extends TestCase
{
    /**
    * @test
    * @expectedException \ItaliaMultimedia\GestPay\Exceptions\GestPayException
    */
    public function instantiationWithNullParametersThrowsException()
    {
        new GestPay(null, null);
    }
}
