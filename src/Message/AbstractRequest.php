<?php

namespace Omnipay\MPU\Message;

use Omnipay\Common\Exception\InvalidRequestException;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    
    protected $liveEndpoint = 'https://www.mpu-ecommerce.com/Payment/';
    protected $testEndpoint = 'http://122.248.120.252:60145/UAT/Payment/';
    
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }
    
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }
    
    /**
     * @throws InvalidRequestException
     */
    public function validate()
    {
        foreach (func_get_args() as $key) {
            $value = $this->parameters->get($key);
            if (!isset($value)) {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }
    
    protected function sendRequest($method, $endpoint, $data = array())
    {
        $httpRequest = $this->httpClient->createRequest(
          $method,
          $this->getEndpoint() . $endpoint,
          array(),
          $data
        );
        
        
        return $this->httpClient->sendRequest($httpRequest);
    }
    
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

}

