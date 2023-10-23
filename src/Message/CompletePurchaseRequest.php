<?php

namespace Omnipay\PayTR\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * PayTR Complete Purchase Request
 *
 * (c) Serdar TAYLAN
 * http://www.github.com/serdartaylan/omnipay-paytr
 */
class CompletePurchaseRequest extends BasePurchaseRequest
{


    public function getData()
    {
    }

    public function sendData($data)
    {
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchantKey', $value);
    }

    public function getMerchantSalt()
    {
        return $this->getParameter('merchantSalt');
    }

    public function setMerchantSalt($value)
    {
        return $this->setParameter('merchantSalt', $value);
    }
}
