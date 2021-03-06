<?php

namespace Omnipay\MPU\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * MPU Purchase Request
 */
class PurchaseRequest extends AbstractRequest {
    
    
    /**
     * Get the raw data array for this message. The format of this varies from
     * gateway to gateway, but will usually be either an associative array, or
     * a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('description', 'amount', 'transactionId');
        
        
        $data = array();
    
        /**
         * Merchant ID
         *
         * Length: 15, Mandatory: Y
         *
         * Merchant ID generated by MPU's acquiringBank.
         */
        $data['merchantID'] = sprintf('%015d', $this->getMerchantId());
    
        /**
         * Unique Invoice No
         *
         * Length: 20, Mandatory: Y
         *
         * Provided by Merchant. The invoice number needs to be unique to
         * trace the transaction. Please pad '0' to the left in the case of
         * generated invoice number length is shorter than 20.
         */
        $data['invoiceNo'] = sprintf('%020d', $this->getTransactionId());
    
        /**
         * Product Description
         *
         * Length: 50, Mandatory: Y
         *
         * The product description to be provided by the merchant.
         */
        $data['productDesc'] = substr($this->getDescription(), 0, 50);
        
        /**
         * Transaction Amount
         *
         * Length: 12, Mandatory: Y
         *
         * The amount needs to be padded with ‘0’ from the left and include no
         * decimal point.
         * Example: 1.00 = 000000000100,
         * 1.5 = 000000000150
         * Currency exponent follows standard ISO4217 currency codes.
         */
        $data['amount'] = sprintf('%012d', $this->getAmountInteger());
    
        /**
         * Standard ISO4217 Currency Codes.
         *
         * Length: 3, Mandatory: Y
         *
         * Refer to Appendix D
         */
        $data['currencyCode'] = sprintf('%03d', $this->getCurrencyNumeric());
    
        /**
         * Category Code
         *
         * Length: 20, Mandatory: N
         *
         * Merchant can distinct the transactions by adding category code.
         */
        $data['categoryCode'] = null;
    
        /**
         * Merchant Defined Information
         *
         * Length: 150, Mandatory: N
         *
         * (Optional) MPU system will response back to merchant whatever
         * information include in request message of this field.
         */
        $data['UserDefined1'] = null;
    
        /**
         * Merchant Defined Information
         *
         * Length: 150, Mandatory: N
         *
         * (Optional) MPU system will response back to merchant whatever
         * information include in request message of this field.
         */
        $data['UserDefined2'] = null;
    
        /**
         * Merchant Defined Information
         *
         * Length: 150, Mandatory: N
         *
         * (Optional) MPU system will response back to merchant whatever
         * information include in request message of this field.
         */
        $data['UserDefined3'] = null;
        
        
        
        return $data;
    }
    
    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        // Remove data that equals null
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        
        /**
         * Hash value computed by HMACSHA1 with secret key provided by MPU
         * System.
         *
         * Length: 150, Mandatory: Y
         *
         * hashValue will be generated by the following methods:
         *  1. All the input parameters which are filled in by the customer,
         *     are sorted by ASCII to be Signature String e.g.
         *     00000005000015082334455555764Invoice00345MerchantID01Product01UserDefined1UserDefined2UserDefined3
         *  2. Signature String will be encrypted by HMAC1 with secret key
         *     provided by MPU System.
         */
        // Sort the data array by ASCII
        $sortedData = $data;
        sort($sortedData, SORT_REGULAR);
        
        $data['hashValue'] = strtoupper(
            hash_hmac('sha1', join("", $sortedData),
                $this->getApiKey())
        );
        
        $httpResponse = $this->sendRequest('POST', 'Payment/pay', $data);
        return $this->response = new PurchaseResponse($this,
            $httpResponse->json());
    }
}