<?php
namespace ItaliaMultimedia\GestPay;

use WebServCo\Framework\Http\Method;

final class Payment extends AbstractGestPay
{
    protected $itemType;

    public function create($amount, $shopTransactionId, $extraData = [])
    {
        if (empty($this->shopLogin)) {
            throw new \InvalidArgumentException('Missing shopLogin');
        }
        $data = array_merge(
            [
                'shopLogin' => $this->shopLogin,
                'shopTransactionID' => $shopTransactionId,
                'itemType' => $this->itemType,
                'amount' => floatval($amount),
                'currency' => $this->currency,
            ],
            $extraData
        );
        return $this->call('payment/create', Method::POST, [], $data);
    }

    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }
}
