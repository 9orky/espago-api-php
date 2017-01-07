# Espago API (PHP) [![Build Status](https://travis-ci.org/9orky/espago-api-php.svg?branch=master)](https://travis-ci.org/9orky/espago-api-php)

Unofficial client of the Espago API written in PHP.

# Installation
To install via Composer just type:

```bash
composer require "9orky/espago-api-php"
```
and you're done!

# Usage
To avoid a one huge file with all API methods, client was split into smaller 
functional pieces. The facade APIs are behind is at: __Gorky\Espago\Factory\ApiFactory__

## Bootstrap
All you need to do is to instantiate an API Factory with:
* apiUrl - this is quite obvious, for development purposes your URL is: https://sandbox.espago.com
* apiCredentials - this is an object which resides at __Gorky\Espago\Model\ApiCredentials__ and requires three arguments
which should be provided to you by Espago: public key, app ID and password.

How does this look in the code?

```php
$apiFactory = new \Gorky\Espago\Factory\ApiFactory(
    'https://sandbox.espago.com',
    new \Gorky\Espago\Model\ApiCredentials('app_id', 'public_key', 'password')
);
```

# Conducting a simplest transaction
Ok, now with our Api Factory handy we can proceed to making our first transaction. What we need is to ask a Customer
for his credit card details and charge him. 

## Obtaining a Token
First step to achieve this is to generate a special token which will be a card's representation in Espago.

```php
$tokensApi = $apiFactory->buildTokensApi();

// first we need Customer card's representation
$unauthorizedCard = $tokensApi->createUnauthorizedCard(
    '4111111111111111',
    'John',
    'Doe',
    '04',
    '2020',
    '111'
);

// so now we can transform it to a Token
$token = $tokensApi->createToken($unauthorizedCard);
```

Token's model is a Response object so, together with others, it resides at: __Gorky\Espago\Model__ directory. Explore the 
area to be familiar with what system can handle.

## Making a Charge
We have a Token so now it's time to charge a Customer. To do this we have to call Charges API and provide some payment 
details. 

```php
$chargesApi = $apiFactory->buildChargesApi();

// carefuly study Gorky\Espago\Model\Response\Charge and corresponding API documents!
$charge = $chargesApi->createChargeByToken($token, 12.66, 'PLN', 'doughnuts');
```

Yes, it was actually that easy :-)

## Remembering the Card in Espago
At this moment payment transaction is executed and Customer is charged. But wait, he just forgot to buy his wife some flowers
for their anniversary. Oh no, he must type all these numbers and letters once again. But no worries though, we can actually
save Customer's card in Espago for us and use it when needed.

To match card with a Customer we need to create a Client. We have do this with a little help of our friend Token.

```php
$tokensApi = $apiFactory->buildTokensApi();

$unauthorizedCard = $tokensApi->createUnauthorizedCard(
    '4111111111111111',
    'John',
    'Doe',
    '04',
    '2020',
    '111'
);

$token = $tokensApi->createToken($unauthorizedCard);

$clientsApi = $apiFactory->buildClientsApi();

$client = $clientsApi->createClient($token, 'john@example.com', 'Our precious client John');

$chargesApi = $apiFactory->buildChargesApi();

$charge = $chargesApi->createChargeByClient($client, 12.66, 'PLN', 'doughnuts');
```

All you have to do now is to persist a Client's ID and from now on you can charge Customer on demand. It's very convenient
when your Customers are returning ones.

# Fancy
There is a simple Console Application to play with on your sandbox account! :-) To play with that you have to make 
some preparations first.

1. In a main directory create PHP file called: _credentials.php_:
```php
// credentials.php

return [
    'app_id'     => 'your_app_id',
    'public_key' => 'your_public_key',
    'password'   => 'your_password'
];
```

2. Party time :-)

Create new Client:

```bash
php espago.php customer:create
```

Charge Client:

```bash
php espago.php customer:charge --interactive
```

or

```bash
php espago.php customer:charge --clientId="1234" --amount="6.67" --currency="PLN" --description="flowers"
```

Capture Authorization hold:

```bash
php espago.php customer:charge --capture --chargeId="pay_COy6zH9fLj1d7K" --amount="23.44"
```

Refund Charge:

```bash
php espago.php customer:charge --refund --chargeId="pay_COy6zH9fLj1d7K" --amount="23.44"
```

Cancel Charge:

```bash
php espago.php customer:charge --cancel --chargeId="pay_COy6zH9fLj1d7K"
```

Note: Remember that you can always use --interactive switch which is more convenient:

```bash
php espago.php customer:charge --cancel --interactive
```

API documentation is right here: 
[https://developers.espago.com/en/v3#201-preliminary-assumptions](https://developers.espago.com/en/v3#201-preliminary-assumptions)
