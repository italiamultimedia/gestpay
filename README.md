# italiamultimedia/gestpay

GestPay REST API Implementation

---
## Installation

```sh
composer require italiamultimedia/gestpay
```

---

## Usage

### Payment API

`\ItaliaMultimedia\GestPay\Payment`

#### Create Payment

```php
$payment = new \ItaliaMultimedia\GestPay\Payment($apiKey, $logDir);
// Set environment (optional, defaults to 'Environment::SANDBOX')
$payment->setEnvironment(\ItaliaMultimedia\GestPay\Environment::SANDBOX);
// Set currency (optional, defaults to `Currencies::EUR`)
$payment->setCurrency(\ItaliaMultimedia\GestPay\Currencies::EUR);
// Set shopLogin
$payment->setShopLogin($shopLogin);
// Set itemType (digital/physical)
$payment->setItemType('digital');
// Create payment
$result = $payment->create($amount, $shopTransactionId);
```

##### Example

[examples/paymentCreate.php](/examples/paymentCreate.php)

```sh
php examples/paymentCreate.php
```

---
