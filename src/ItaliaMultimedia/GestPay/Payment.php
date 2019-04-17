<?php
namespace ItaliaMultimedia\GestPay;

use WebServCo\Framework\Http\Method;

final class Payment extends AbstractGestPay
{
    protected $itemType;

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
                'shopTransactionID' => $shopTransactionId,
                'itemType' => $this->itemType,
                'amount' => floatval($amount),
                'currency' => $this->currency,
            ]
        );
    }

    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }
}
