<?php
namespace ItaliaMultimedia\GestPay;

use WebServCo\Framework\Http\Method;

final class Payment extends AbstractGestPay
{
    protected $itemType;

    public function create($amount, $shopTransactionId, $extraData = [])
    {
        $this->validatePayment();
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

    public function detail($extraData = [])
    {
        $this->validatePayment();
        $data = array_merge(
            [
                'shopLogin' => $this->shopLogin,
            ],
            $extraData
        );
        return $this->call('payment/detail', Method::POST, [], $data);
    }

    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }

    protected function validatePayment()
    {
        if (empty($this->shopLogin)) {
            throw new \InvalidArgumentException('Missing shopLogin');
        }
        return true;
    }
}
