<?php

namespace Omnipay\PayTR\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * PayTR Response
 *
 * (c) Serdar TAYLAN
 * http://www.github.com/serdartaylan/omnipay-paytr
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{

	/**
	 * Constructor
	 *
	 * @param  RequestInterface         $request
	 * @param  string                   $data / response data
	 * @throws InvalidResponseException
	 */
	public function __construct(RequestInterface $request, $data)
	{
		$this->request = $request;
		try {
			$this->data = json_decode($data, 1);
		} catch (\Exception $ex) {
			throw new InvalidResponseException();
		}
	}

	/**
	 * Whether or not response is successful
	 *
	 * @return bool
	 */
	public function isSuccessful()
	{
		if (isset($this->data["status"])) {
			return (string) $this->data["status"] === 'success';
		} else {
			return false;
		}
	}

	/**
	 * Get is redirect
	 *
	 * @return bool
	 */
	public function isRedirect()
	{
		return false;
	}

	/**
	 * Get a code describing the status of this response.
	 *
	 * @return string|null code
	 */
	public function getCode()
	{
		return $this->isSuccessful() ? $this->data["reason"] : parent::getCode();
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

	/**
	 * Get error
	 *
	 * @return string
	 */
	public function getError()
	{
		return $this->data["reason"];
	}

	/**
	 * Get Redirect url
	 *
	 * @return string
	 */
	public function getRedirectUrl()
	{
		if ($this->isRedirect()) {
			$data = array(
				'TransId' => $this->data["hostlogkey"]
			);
			return $this->getRequest()->getEndpoint() . '/test/index?' . http_build_query($data);
		}
	}

	/**
	 * Get Redirect method
	 *
	 * @return POST
	 */
	public function getRedirectMethod()
	{
		return 'POST';
	}

	/**
	 * Get Redirect url
	 *
	 * @return null
	 */
	public function getRedirectData()
	{
		return null;
	}

	/**
	 * is complate url
	 *
	 * @return null
	 */
	public function isComplateUrl()
	{
	}
}
