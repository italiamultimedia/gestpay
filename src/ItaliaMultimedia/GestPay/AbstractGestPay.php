<?php
namespace ItaliaMultimedia\GestPay;

abstract class AbstractGestPay
{
    protected $apiKey;
    protected $currency;
    protected $shopLogin;

    public function __construct($apiKey, $shopLogin)
    {
        $this->validate($apiKey, $shopLogin);
        $this->apiKey = $apiKey;
        $this->currency = Currencies::EUR;
        $this->shopLogin = $shopLogin;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    protected function validate($apiKey, $shopLogin)
    {
        if (empty($apiKey) || empty($shopLogin)) {
            throw new \InvalidArgumentException('Missing required parameter(s)');
        }
    }
}
