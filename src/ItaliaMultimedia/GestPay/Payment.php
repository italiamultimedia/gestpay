<?php
namespace ItaliaMultimedia\GestPay;

use WebServCo\Framework\Http\Method;

final class Payment extends AbstractGestPay
{
    public function create($amount, $shopTransactionId)
    {
        if (empty($this->shopLogin)) {
            throw new \InvalidArgumentException('Missing shopLogin');
        }
        return $this->call(
            'payment/create',
            Method::POST,
            [], // headers
            [
                'shopLogin' => $this->shopLogin,
                'amount' => floatval($amount),
                'currency' => $this->currency,
                'shopTransactionID' => $shopTransactionId,
            ]
        );
    }
}
