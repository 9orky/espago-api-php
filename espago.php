#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;

$credentialsFilePath = sprintf('%s/credentials.php', __DIR__);

try {
    if (!is_file($credentialsFilePath)) {
        throw new Exception(sprintf('Credentials file does not exist at this location: %s', $credentialsFilePath));
    }

    if (!$credentials = require($credentialsFilePath)) {
        throw new Exception(
            'Credentials file exists but does not contain any data.\
            Please, take a look at configuration instructions for this Dev Console'
        );
    }
} catch (\Exception $e) {
    echo "\n{$e->getMessage()}\n";

    return -1;
}

$apiProvider = new \Gorky\Espago\ApiProvider(
    'https://sandbox.espago.com',
    new \Gorky\Espago\Value\ApiCredentials(
        $credentials['app_id'],
        $credentials['public_key'],
        $credentials['password']
    )
);

$application = new Application();

$application->add(new \Gorky\Espago\Command\CreateCustomerCommand('customer:create', $apiProvider));
$application->add(new \Gorky\Espago\Command\ChargeCustomerCommand('customer:charge', $apiProvider));

$application->run();
