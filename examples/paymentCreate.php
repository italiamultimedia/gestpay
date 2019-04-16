<?php
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;

$projectPath = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR;
require $projectPath . 'vendor/autoload.php';

/* General settings */

$logDir = sprintf('%svar/tmp/', $projectPath);
$apiKey = 'R0VTUEFZNzM3NTgjI0VzZXJjZW50ZSBUZXN0IGRpIEJBUkJJRVJJIyMxNi8wNC8yMDE5IDEwOjQ0OjQx';
$shopLogin = 'GESPAY73758';

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
    // Create payment
    $result = $payment->create($amount, $shopTransactionId);
    $logger->debug('Result:', $result);
} catch (\ItaliaMultimedia\GestPay\Exceptions\GestPayException $e) {
    $logger->error(Ansi::sgr(sprintf('Error: %s: %s', $e->getCode(), $e->getMessage()), [Sgr::RED]));
}
