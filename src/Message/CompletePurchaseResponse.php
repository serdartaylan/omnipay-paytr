<?php

namespace Omnipay\PayTR\Message;

use Exception;
use Omnipay\Common\Message\AbstractResponse;

/**
 * PayTR Response
 *
 * (c) Serdar TAYLAN
 * http://www.github.com/serdartaylan/omnipay-paytr
 */
class Response extends AbstractResponse
{

    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param string $data / response data
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        try {
            $this->data = json_decode($data, 1);
        } catch (Exception $ex) {
            throw new InvalidResponseException();
        }
    }

    /**
     * Get transaction reference
     *
     * @return string
     */
    public function getTransactionReference()
    {

        return $this->isSuccessful() ? $this->data["token"] : '';
    }

    /**
     * Whether or not response is successful
     *
     * @return bool
     */
    public function isSuccessful()
    {
        if (isset($this->data["status"])) {
            return (string)$this->data["status"] === 'success';
        } else {
            return false;
        }
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        if ($this->isSuccessful()) {
            return $this->data["token"];
        }
        return $this->data["reason"];
    }
}
