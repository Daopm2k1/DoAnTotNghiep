<?php
/**
 * eWay Rapid 3.1 Gateway
 */

namespace Omnipay\Eway31;

/**
 * eWay Rapid 3.1 Gateway
 *
 * All of the functionality of the Rapid 3.1 gateway is the same as the
 * functionality of the Rapid 3.0 gateway except for the 3 functions
 * extended here.
 *
 * Usage is described in \Omnipay\Common\AbstractGateway.
 *
 * @see \Omnipay\Common\AbstractGateway
 * @reference http://www.eway.com.au/
 * @reference http://en.wikipedia.org/wiki/EWay
 * @reference http://www.eway.com.au/developers/cool-stuff/sandbox-testing for sandbox testing
 */
class RapidGateway extends \Omnipay\Eway\RapidGateway
{
    public function getName()
    {
        return 'eWAY Rapid 3.1';
    }

    /**
     * Store a credit card in the gateway and return a token.
     *
     * @param array $parameters
     * @return \Omnipay\Eway31\Message\CreateCardRequest
     */
    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway31\Message\CreateCardRequest', $parameters);
    }

    /**
     * Update a stored credit card in the gateway.
     *
     * @param array $parameters
     * @return \\Omnipay\Eway31\Message\UpdateCardRequest
     */
    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway31\Message\UpdateCardRequest', $parameters);
    }

    /**
     * Create a purchase request.
     *
     * @param array $parameters
     * @return \Omnipay\Eway31\Message\RapidPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Eway31\Message\RapidPurchaseRequest', $parameters);
    }
}
