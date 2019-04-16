<?php
namespace ItaliaMultimedia\GestPay;

use ItaliaMultimedia\GestPay\Exceptions\GestPayException;
use WebServCo\Framework\Http\Method;

abstract class AbstractGestPay
{
    protected $apiKey;
    protected $httpBrowserInterface;
    protected $currency;
    protected $environment;
    protected $shopLogin;
    protected $url;

    public function __construct($apiKey, $logDir)
    {
        $this->validate($apiKey, $logDir);
        $this->apiKey = $apiKey;
        $this->currency = Currencies::EUR;
        $this->environment = Environment::SANDBOX;

        $browserLogger = new \WebServCo\Framework\Log\FileLogger(
            'GestpayApiBrowser',
            $logDir,
            \WebServCo\Framework\Framework::library('Request')
        );
        $this->httpBrowserInterface = new \WebServCo\Framework\CurlBrowser($browserLogger);
        $this->httpBrowserInterface->setDebug(true);
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    public function setShopLogin($shopLogin)
    {
        $this->shopLogin = $shopLogin;
    }

    protected function call($endpoint, $method, $headers = [], $data = null)
    {
        $url = sprintf('%s%s', $this->getApiUrl(), $endpoint);

        switch ($method) {
            case Method::GET:
                break;
            case Method::POST:
                if (!empty($data)) {
                    if (is_array($data)) {
                        $data = json_encode($data);
                    }
                    $this->httpBrowserInterface->setRequestContentType('application/json');
                    $this->httpBrowserInterface->setRequestData($data);
                }
                break;
            default:
                throw new GestPayException('Method not implemented.');
                break;
        }
        $this->httpBrowserInterface->setMethod($method);

        $this->setAuthorizationHeader();

        if (is_array($headers)) {
            foreach ($headers as $key => $value) {
                $this->httpBrowserInterface->setRequestHeader($key, $value);
            }
        }

        $response = $this->httpBrowserInterface->retrieve($url); // \WebServCo\Framework\Http\Response

        $apiResponse = new ApiResponse($endpoint, $method, $response); // \WebServCo\DiscogsApi\ApiResponse

        $status = $apiResponse->getStatus();
        $data = $apiResponse->getData();

        if (in_array($status, [200]) && isset($data['payload'])) {
            return $data['payload'];
        }

        $errorCode = isset($data['error']['code']) ? $data['error']['code'] : 0;
        $errorDescription = isset($data['error']['description']) ? $data['error']['description'] : 'GestPay API Error';

        throw new \ItaliaMultimedia\GestPay\Exceptions\ResponseException($errorDescription, $errorCode);
    }

    protected function getApiUrl()
    {
        switch ($this->environment) {
            case Environment::PRODUCTION:
                return Url::PRODUCTION;
                break;
            case Environment::SANDBOX:
                return Url::SANDBOX;
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Invalid environment: %s', $this->environment));
                break;
        }
    }

    protected function setAuthorizationHeader()
    {
        $this->httpBrowserInterface->setRequestHeader('Authorization', sprintf('apikey %s', $this->apiKey));
    }

    protected function validate($apiKey, $logDir)
    {
        if (empty($apiKey) || empty($logDir)) {
            throw new \InvalidArgumentException('Missing required parameter(s)');
        }

        if (!is_writable($logDir)) {
            throw new GestPayException('Log directory not writable');
        }
    }
}
