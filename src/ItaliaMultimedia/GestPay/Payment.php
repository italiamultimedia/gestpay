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
                'shopLogin' => (string) $this->shopLogin,
                'shopTransactionID' => (string) $shopTransactionId,
                'itemType' => (string) $this->itemType,
                'amount' => (string) $amount,
                'currency' => (string) $this->currency,
            ],
            $extraData
        );
        return $this->call('payment/create', Method::POST, [], $this->parseData($data));
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
        return $this->call('payment/detail', Method::POST, [], $this->parseData($data));
    }

    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }

    protected function parseData($data = [])
    {
        $parsedData = [];
        foreach ($data as $key => $value) {
            $parsedData[$key] = (string) $value;
        }
        return $parsedData;
    }

    protected function validatePayment()
    {
        if (empty($this->shopLogin)) {
            throw new \InvalidArgumentException('Missing shopLogin');
        }
        return true;
    }

    public function submit($paymentToken, $bodyExtraData = [])
    {
        $headers = [
            'paymentToken' => $paymentToken,
            'Content-Type' => 'application/json'
        ];

        $data = array_merge(
            [
                'buyer' => [
                    'email' => 'test@test.com',
                    'name' => '123123'
                ],
                'shopLogin' => (string) $this->shopLogin,
                'paymentTypeDetails' => [
                    'creditcard' => [
                        'number' => '4012001037141112',
                        'expMonth' => '05',
                        'expYear' => '27',
                        'CVV' => '444',
                        'DCC' => null
                    ]
                ]
            ],
            $bodyExtraData
        );
        return $this->call('payment/submit', Method::POST, $headers, $data);
    }
}
