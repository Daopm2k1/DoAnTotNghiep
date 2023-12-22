<?php
/**
 * eWay Rapid 3.1 Gateway
 */

namespace Omnipay\Eway31;
use Omnipay\Eway31\Message\SimpleResponse;

/**
 * eWay Old Gateway
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
 * Eway class
 * @link http://www.eway.com.au/_Files/documentation/Token%20Payments%20Field%20Description.pdf
 */

class OldGateway extends \Omnipay\Eway\RapidGateway
{

    public function getName()
    {
        return 'eWAY Old';
    }

    /**
     * Store a credit card in the gateway and return a token.
     *
     * @param array $parameters
     * @return \Omnipay\Eway31\Message\CreateCardRequest
     */
    public function createCard(array $parameters = array())
    {
        return $this->generateManagedCustomerId($parameters['card'], $parameters['customerReference']);
    }

    /**
     * Store a credit card in the gateway and return a token.
     *
     * @param array $parameters
     * @return \Omnipay\Eway31\Message\CreateCardRequest
     */
    public function updateCard(array $parameters = array())
    {
        return $this->updatePaymentDetails($parameters['card'], $parameters['customerReference'], $parameters['cardReference']);
    }

    /**
     * Process a payment
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|void
     */
    public function purchase(array $parameters = array())
    {
        $this->processPayment($parameters['cardReference'], $parameters['amount'], $parameters['transactonId']);
        return $this;
    }

    protected $testMode = false;
    protected $customerId = '';
    protected $username = '';
    protected $password = '';
    protected $wsdlUrl = 'https://www.eway.com.au/gateway/ManagedPaymentService/managedCreditCardPayment.asmx?WSDL';
    protected $url = '';
    protected $country = 'au';
    protected $state = 'NSW';
    protected $namespace = 'https://www.eway.com.au/gateway/managedpayment';
    protected $error = '0';
    protected $response = '';

    /**
     * Sets the api key
     *
     * @param string $key
     * @return void
     */
    public function setApiKey($key) {
        $this->username = $key;
    }

    /**
     * Sets the password
     *
     * @param string $password
     * @return void
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Sets the test model
     *
     * @param boolean $mode
     * @return void
     */
    public function setTestMode($mode) {
        $this->testMode = $mode;
    }

    /**
     * Get the WDSL URL
     *
     * @return string
     */
    public function getWsdlUrl() {
        if($this->testMode) {
            return 'https://www.eway.com.au/gateway/ManagedPaymentService/test/managedCreditCardPayment.asmx?WSDL';
        }
        return $this->wsdlUrl;
    }

    /**
     * Get the customer id
     *
     * @return string
     */
    public function getCustomerId() {
        if($this->testMode) {
            return '87654321';
        }
        return $this->customerId;
    }

    /**
     * Get the customer id
     *
     * @return string
     */
    public function getUsername() {
        if($this->testMode) {
            return 'test@eway.com.au';
        }
        return $this->username;
    }
    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        if($this->testMode) {
            return 'test123';
        }
        return $this->password;
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     * @return CreditCard provides a fluent interface.
     */
    public function init(array $parameters = array())
    {
        $this->customerId = $parameters['customerId'];
        $this->url = $parameters['url'];
    }

    /**
     * Send the request
     *
     * @return \stdClass
     */
    public function send()
    {
        return $this->response;
    }

    /**
     * generate managed customerId(gaetwayid) stored in the Contact table
     *
     * @param \Omnipay\Common\CreditCard $card
     * @param string $customerReference
     * @return number
     */
    public function generateManagedCustomerId($card, $customerReference)
    {
        $client = $this->getClient();
        $params = $this->generateParams($card, $customerReference);
        try {
            $soap_response = $client->CreateCustomer($params);
            $this->response = $soap_response->CreateCustomerResult ?
                (new SimpleResponse)->setIsSuccessful(true)->setMessage($soap_response->CreateCustomerResult) :
                (new SimpleResponse)->setIsSuccessful(false)->setMessage('Unknown error happened on update customer payment details');
        } catch (\SoapFault $fault) {
            $this->response = (new SimpleResponse)->setIsSuccessful(false)->setMessage($fault->getMessage());
        }
        return $this;
    }

    /**
     * process payment by managedCustomerID
     *
     * @param long $member_gateway_id
     * @param float $amount
     * @param string $invoice_no
     * @return number
     */
    public function processPayment($member_gateway_id, $amount, $invoice_no)
    {
        $client = $this->getClient();
        $params = [
            'managedCustomerID' => (float) $member_gateway_id,
            'amount' => $amount * 100,
            'invoiceReference' => $invoice_no,
        ];

        try {
            $soap_response = $client->ProcessPayment($params);
            $this->response = strtolower($soap_response->ewayResponse->ewayTrxnStatus) == 'true' ?
                (new SimpleResponse)->setIsSuccessful(true)->setCode('A2000') :
                (new SimpleResponse)->setIsSuccessful(false)->setMessage($soap_response->ewayResponse->ewayTrxnError);
        } catch (\SoapFault $fault) {
            $this->response = (new SimpleResponse)->setIsSuccessful(false)->setMessage($fault->getMessage());
        }

        return $this;
    }

    /**
     * Update payment details
     * @param \Omnipay\Common\CreditCard $card
     * @param string $customerReference
     * @param string $cardReference
     * @return number
     */
    public function updatePaymentDetails($card, $customerReference, $cardReference)
    {
        $client = $this->getClient();
        $params = $this->generateParams($card, $customerReference, $cardReference);

        try {
            $soap_response = $client->UpdateCustomer($params);
            $this->response = $soap_response->UpdateCustomerResult ?
                (new SimpleResponse)->setIsSuccessful(true)->setMessage($soap_response->UpdateCustomerResult) :
                (new SimpleResponse)->setIsSuccessful(false)->setMessage('Unknown error happened on update customer payment details');
        } catch (\SoapFault $fault) {
            $this->response = (new SimpleResponse)->setIsSuccessful(false)->setMessage($fault->getMessage());
        }
        return $this;
    }

    /**
     * generate the soap headers
     *
     * @return \SoapHeader
     */
    private function generateSoapHeader()
    {
        $authParams = [
            'eWAYCustomerID' => $this->getCustomerId(),
            'Username' => $this->getUsername(),
            'Password' => $this->getPassword()
        ];
        $header = new \SoapHeader($this->namespace, "eWAYHeader", $authParams);
        return $header;
    }

    /**
     * generate the soap headers
     *
     * @return \SoapHeader
     */
    private function generateParams($card, $customerReference, $cardReference = null)
    {
        $params = [
            'Title' => '',
            'FirstName' => $card->getFirstName(),
            'LastName' => $card->getLastName(),
            'Country' => $this->country,
            'Email' => $card->getEmail() ? $card->getEmail() : "",
            'URL' => $this->url,
            'Address'=> '',
            'Suburb'=> '',
            'State' => $this->state,
            'Company' => '',
            'PostCode' => '',
            'Fax' => '',
            'Phone' => '',
            'Mobile' => '',
            'CustomerRef' => $customerReference,
            'JobDesc' => '',
            'Comments' => '',
            'URL' => $this->url,
            'CCNumber' => $this->testMode ? '4444333322221111' : str_replace(' ', '', $card->getNumber()),
            'CCNameOnCard' => $card->getName(),
            'CCExpiryMonth' => $card->getExpiryMonth(),
            'CCExpiryYear' => $card->getExpiryYear() % 100,
            'CVN' => $this->testMode ? '123' : $card->getCvv()
        ];
        if($cardReference) {
            $params['managedCustomerID'] = $this->testMode ? '9876543211000' : (float) $cardReference;
        }
        return $params;
    }

    /**
     * @return \SoapClient
     */
    protected function getClient()
    {
        $client = new \SoapClient($this->getWsdlUrl(), array('trace' => true));
        $headers = $this->generateSoapHeader();
        $client->__setSoapHeaders(array($headers));
        return $client;
    }
}
