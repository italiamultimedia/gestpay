# italiamultimedia/gestpay

GestPay REST API Implementation.

## Notes
* Supports only `apiKey` Authorization

## Implemented endpoints
* `payment/create`
* `payment/detail`

---
## Installation

```sh
composer require italiamultimedia/gestpay
```

---

## Usage

### Payment API

`\ItaliaMultimedia\GestPay\Payment`

General payment class initialization
```php
$payment = new \ItaliaMultimedia\GestPay\Payment($apiKey, $logDir);
// Set environment (optional, defaults to 'Environment::SANDBOX')
$payment->setEnvironment(\ItaliaMultimedia\GestPay\Environment::SANDBOX);
// Set currency (optional, defaults to `Currencies::EUR`)
$payment->setCurrency(\ItaliaMultimedia\GestPay\Currencies::EUR);
// Set shopLogin
$payment->setShopLogin($shopLogin);
```

#### POST `payment/create`

> https://api.gestpay.it/#post-payment-create

```php
// Set itemType (digital/physical)
$payment->setItemType('digital');
// Set optional data
$extraData = [
    'languageId' => 2,
];
// Create payment
$result = $payment->create($amount, $shopTransactionId, $extraData);
```

##### Example

[examples/paymentCreate.php](/examples/paymentCreate.php)

```sh
php examples/paymentCreate.php <apiKey> <shopLogin>
```

#### `POST` `payment/detail`

> https://api.gestpay.it/#post-payment-detail

Optionally, authenticate using `paymentToken` instead of `Authorization`:
```php
$payment->setPaymentToken($paymentToken);
```

```php
// Set optional data (you must provide at least one of `shopTransactionID`, `bankTransactionID`, `paymentID`.)
$extraData = [
    'paymentID' => $paymentId,
];
// Get payment detail
$result = $payment->detail($extraData);
```

##### Example

[examples/paymentDetail.php](/examples/paymentDetail.php)

```sh
php examples/paymentDetail.php <apiKey> <shopLogin> <paymentId>
```
---

## Lightbox example

[examples/lightbox.php](/examples/lightbox.php)

> See Gestpay documentation: [Using Lightbox solution](https://docs.gestpay.it/rest/getting-started/getting-started/#using-lightbox-solution)
