<?php
namespace Tests\GestPay;

use PHPUnit\Framework\TestCase;
use ItaliaMultimedia\GestPay\Payment;

final class PaymentTest extends TestCase
{
    /**
    * @test
    * @expectedException \InvalidArgumentException
    */
    public function instantiationWithNullParametersThrowsException()
    {
        new Payment(null, null);
    }
}
