# Omnipay: eWay31

**eWay driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements eWay support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "tez/omnipay-eway": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Eway_Rapid31

For general usage instructions, please see the main [Omnipay](https://github.com/Mihai-P/tez-omnipay-eway)
repository.

## Examples

```php
use Omnipay\Omnipay;

// Create the gateway object and set its parameters
$gateway = Omnipay::create('Eway31_Rapid');
$gateway->setTestMode(true);
$gateway->setApiKey(\Yii::$app->params['eway']['key']);
$gateway->setPassword(\Yii::$app->params['eway']['password']);

// Create a CreditCard object which we intend to validate
// via the gateway
$card = new CreditCard([
    'firstName' => 'Bobby',
    'lastName' => 'Tables',
    'number' => '4444333322221111',
    'cvv' => '123',
    'expiryMonth' => 12,
    'expiryYear' => '2017',
    'email' => 'testEway@biti.ro',
]);

// Use the gateway createCard method to determine if creating
// the card in the eWay gateway is successful.
$response = $gateway->createCard(['card' => $card])->send();
if ($response->isSuccessful()) {
    $tokenCustomerID = $response->getTokenCustomerID();
    echo "We have a token ID<br>";
    // payment was successful: update database
} elseif ($response->isRedirect()) {
    // redirect to offsite payment gateway
    $response->redirect();
    die('error');
} else {
    // payment failed: display message to customer
    print_r($response->getCode());
    print_r($response);
    die('payment failed');
}

// if we got a tokenCustomerId then we can use it for further transactions.
if(! empty($tokenCustomerID)) {
    
    // Make a second credit card object.
    $card2 = new CreditCard([
        'firstName' => 'Mi',
        'lastName' => 'Pe',
        'number' => '4444333322221111',
        'cvv' => '123',
        'expiryMonth' => 12,
        'expiryYear' => '2016',
        'email' => 'testEway@biti.ro',
    ]);
    
    // Update the card on the token.
    echo "UPDATE<br>";
    $response = $gateway->updateCard(['cardReference' => $tokenCustomerID, 'card' => $card2])->send();
    print_r($response->getCode());

    // Put through a purchase transaction with the updated card.
    echo "PURCHASE<br>";
    $response = $gateway->purchase(['amount' => '10.00', 'cardReference' => $tokenCustomerID, 'transactonId' => 'Invoice1', 'description' => 'Invoice1 billed', 'currency' => 'AUD'])->send();
    //print_r($response);
    print_r($response->getCode());
    
    // Make a third credit card object.
    $card3 = new CreditCard([
        'firstName' => 'Mi',
        'lastName' => 'Pe',
        //'cvv' => '123',
    ]);

    // Put through a purchase transaction with the third card.
    echo "PURCHASE<br>";
    $response = $gateway->purchase(['amount' => '10.00', 'cardReference' => $tokenCustomerID, 'transactonId' => 'Invoice1', 'description' => 'Invoice1 billed', 'currency' => 'AUD', 'card' => $card3])->send();
    //print_r($response);
    print_r($response->getCode());
}
```
 