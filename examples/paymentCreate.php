<?php
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;

$projectPath = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR;
require $projectPath . 'vendor/autoload.php';

/* General settings */
$apiKey = isset($argv[1]) ? $argv[1] : null;
$shopLogin = isset($argv[2]) ? $argv[2] : null;
$logDir = sprintf('%svar/tmp/', $projectPath);

/* Payment settings */

$amount = 0.99;
$shopTransactionId = 1;

/* Functionality */
$result = false;

$logger = new \WebServCo\Framework\Log\CliOutputLogger();
$logger->clear();
$logger->debug(Ansi::sgr('Payment create', [Sgr::BOLD]));

try {
    $payment = new \ItaliaMultimedia\GestPay\Payment($apiKey, $logDir);
    // Set environment (optional, defaults to 'Environment::SANDBOX')
    $payment->setEnvironment(\ItaliaMultimedia\GestPay\Environment::SANDBOX);
    // Set currency (optional, defaults to `Currencies::EUR`)
    $payment->setCurrency(\ItaliaMultimedia\GestPay\Currencies::EUR);
    // Set shopLogin
    $payment->setShopLogin($shopLogin);
    // Set itemType (digital/physical)
    $payment->setItemType('digital');
    // Set optional data
    $extraData = [
        'languageId' => 2,
    ];
    // Create payment
    $result = $payment->create($amount, $shopTransactionId, $extraData);
    $logger->debug('Result:', $result);
} catch (\ItaliaMultimedia\GestPay\Exceptions\GestPayException $e) {
    $logger->error(Ansi::sgr(sprintf('GestPay Exception: %s: %s', $e->getCode(), $e->getMessage()), [Sgr::RED]));
} catch (\Exception $e) {
    $logger->error(Ansi::sgr(sprintf('Exception: %s: %s', $e->getCode(), $e->getMessage()), [Sgr::RED]));
}
