#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;

$credentialsFilePath = sprintf('%s/credentials.php', __DIR__);

if (!is_file($credentialsFilePath)) {
    throw new Exception(sprintf('Credentials file does not exist at this location: %s', $credentialsFilePath));
}

if (!$credentials = require($credentialsFilePath)) {
    throw new Exception(
        'Credentials file exists but does not contain any data.\
         Please, take a look at configuration instructions for this Dev Console'
    );
}

$apiFactory = new \Gorky\Espago\Factory\ApiFactory(
    new \Gorky\Espago\Factory\HttpCallFactory(
        new \Gorky\Espago\Value\ApiCredentials(
            $credentials['app_id'],
            $credentials['public_key'],
            $credentials['password']
        )
    ),
    new \Gorky\Espago\HttpClient('https://sandbox.espago.com')
);

$application = new Application();

$application->add(new \Gorky\Espago\Command\CreateCustomerCommand('customer:create', $apiFactory));
$application->add(new \Gorky\Espago\Command\ChargeCustomerCommand('customer:charge', $apiFactory));

$application->run();
