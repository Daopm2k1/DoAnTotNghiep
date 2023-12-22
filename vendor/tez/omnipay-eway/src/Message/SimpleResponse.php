<?php
/**
 * eWAY Rapid 3.1 Simple Response
 */

namespace Omnipay\Eway31\Message;

/**
 * eWAY Rapid 3.1 Purchase Response
 */
class SimpleResponse
{
    var $isSuccessful = true;
    var $message = '';
    var $error;
    var $code;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @param boolean $isSuccessful
     */
    public function setIsSuccessful($isSuccessful)
    {
        $this->isSuccessful = $isSuccessful;
        return $this;
    }

    /**
     * Was the call successfull
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->isSuccessful;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenCustomerID()
    {
        return $this->message;
    }
}