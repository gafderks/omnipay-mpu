<?php

/**
 * MPU Gateway.
 */
namespace Omnipay\MPU;

use Omnipay\Common\AbstractGateway;


/**
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{
    
    
    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'MPU';
    }
    
    public function getDefaultParameters()
    {
        return array(
            'apiKey' => '',
        );
    }
    
    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }
    
    /**
     * @param string $value
     *
     * @return $this
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }
    
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }
    
    /**
     * @param int $value
     *
     * @return $this
     */
    public function setMerchantId($value) {
        return $this->setParameter('merchantId', $value);
    }
    
    /**
     * Purchase request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\MPU\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MPU\Message\PurchaseRequest',
            $parameters);
    }
    
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MPU\Message\CompletePurchaseRequest', $parameters);
    }
    
    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MPU\Message\RefundRequest',
            $parameters);
    }
    
    
    
}