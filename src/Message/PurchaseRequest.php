<?php

namespace Omnipay\PayTR\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * PayTR Purchase Request
 *
 * (c) Serdar TAYLAN
 * http://www.github.com/serdartaylan/omnipay-paytr
 */
class PurchaseRequest extends AbstractRequest
{

    protected $endpoint = '';
    protected $endpoints = array(
        'purchase' => 'https://www.paytr.com/odeme/api/get-token'
    );

    public function getData()
    {
        $data['orderID'] = $this->getOrderId();
        return $data;
    }

    public function getOrderId()
    {
        return $this->getParameter('orderid');
    }

    public function sendData($data)
    {

        $email = $this->getCard()->getEmail();
        $payment_amount = $this->getAmountInteger();

        $no_installment = $this->getNoInstallment();
        $max_installment = $this->getMaxInstallment();
        $user_name = $this->getCard()->getFirstName() . " " . $this->getCard()->getLastName();
        $user_address = $this->getCard()->getBillingAddress1() . " " . $this->getCard()->getBillingAddress2();
        $user_phone = $this->getCard()->getBillingPhone();

        $user_basket = base64_encode(json_encode($this->getBasket()));

        $hash = $this->getMerchantNo() . $this->getIp() . $this->getOrderId() . $email . $payment_amount . $user_basket . $no_installment . $max_installment;
        $token = base64_encode(hash_hmac('sha256', $hash . $this->getMerchantSalt(), $this->getMerchantKey(), true));

        $post_vals = array(
            'debug_on' => $this->getTestMode() ? 1 : 0,
            'merchant_id' => $this->getMerchantNo(),
            'user_ip' => $this->getIp(),
            'merchant_oid' => $this->getOrderId(),
            'merchant_ok_url' => $this->getReturnUrl(),
            'merchant_fail_url' => $this->getCancelUrl(),

            'paytr_token' => $token,
            'payment_amount' => $payment_amount,

            'no_installment' => $no_installment,
            'max_installment' => $max_installment,

            'user_basket' => $user_basket,
            'email' => $email,
            'user_name' => $user_name,
            'user_address' => $user_address,
            'user_phone' => $user_phone,
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->endpoints['purchase']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $result = @curl_exec($ch);

        curl_close($ch);

        return $this->response = new Response($this, $result);
    }

    public function getNoInstallment()
    {
        return $this->getParameter('no_installment');
    }

    public function getMaxInstallment()
    {
        return $this->getParameter('max_installment');
    }

    public function getBasket()
    {
        return $this->getParameter('basket');
    }

    public function getMerchantNo()
    {
        return $this->getParameter('merchantNo');
    }

    public function getIp()
    {
        return $this->getParameter('ip');
    }

    public function getMerchantSalt()
    {
        return $this->getParameter('merchantSalt');
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantNo($value)
    {
        return $this->setParameter('merchantNo', $value);
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchantKey', $value);
    }

    public function setMerchantSalt($value)
    {
        return $this->setParameter('merchantSalt', $value);
    }

    public function getInstallment()
    {
        return $this->getParameter('installment');
    }

    public function setInstallment($value)
    {
        return $this->setParameter('installment', $value);
    }

    public function setNoInstallment($value)
    {
        return $this->setParameter('no_installment', $value);
    }

    public function setMaxInstallment($value)
    {
        return $this->setParameter('max_installment', $value);
    }

    public function setIp($value)
    {
        return $this->setParameter('ip', $value);
    }

    public function getTransId()
    {
        return $this->getParameter('transId');
    }

    public function setTransId($value)
    {
        return $this->setParameter('transId', $value);
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderid', $value);
    }

    public function setBasket($value)
    {
        return $this->setParameter('basket', $value);
    }
}
