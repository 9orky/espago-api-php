# Espago API (PHP) [![Build Status](https://travis-ci.org/9orky/espago-api-php.svg?branch=master)](https://travis-ci.org/9orky/espago-api-php)

Unofficial client of the Espago API written in PHP.

## Installation
To install via Composer just type:

```bash
composer require 9orky/espago-api-php
```
and you're done :)

## Usage
To avoid a one huge file with all API methods, client was split into smaller 
functional pieces. The facade APIs are behind is at: Gorky\Espago\ApiProvider.
All you need to do is to instantiate an API Provider with:
* apiUrl - this is quite obvious, for development purposes your URL is: https://sandbox.espago.com
* apiCredentials - this is an object which resides at Gorky\Espago\Model\ApiCredentials and requires three arguments
which should be provided by Espago: public key, app ID and password

How does this look in the code?

```php
$apiProvider = new \Gorky\Espago\ApiProvider(
    'https://sandbox.espago.com',
    new \Gorky\Espago\Model\ApiCredentials('app_id', 'public_key', 'password')
);
```

API documentation is right here: [https://developers.espago.com/en/v3#201-preliminary-assumptions](https://developers.espago.com/en/v3#201-preliminary-assumptions)
