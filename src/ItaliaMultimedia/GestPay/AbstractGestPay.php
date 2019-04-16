<?php
namespace ItaliaMultimedia\GestPay;

abstract class AbstractGestPay
{
    protected $apiKey;
    protected $shopLogin;

    public function __construct($apiKey, $shopLogin)
    {
        $this->validate($apiKey, $shopLogin);
        $this->apiKey = $apiKey;
        $this->shopLogin = $shopLogin;
    }

    protected function validate($apiKey, $shopLogin)
    {
        if (empty($apiKey) || empty($shopLogin)) {
            throw new \InvalidArgumentException('Missing required parameter(s)');
        }
    }
}
