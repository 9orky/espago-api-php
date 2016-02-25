<?php
require 'vendor/autoload.php';

$apiCredentials = new \Gorky\Espago\Value\ApiCredentials('ms_6jipxgyL3', '4kdYkSxSY2t9APLBQw9x', 'yGfuf5sS');
$httpClient = new \Gorky\Espago\Api\Client\HttpClient('https://sandbox.espago.com');
$httpCallBuilder = new \Gorky\Espago\Builder\HttpCallBuilder($apiCredentials);

$espagoApi = new \Gorky\Espago\Api\EspagoApi($apiCredentials, $httpCallBuilder, $httpClient);

$unauthorizedCard = $espagoApi->createUnauthorizedCard('4242424242424242', 'T', 'K', '01', '2017', '123');

$token = $espagoApi->createToken($unauthorizedCard);
var_dump($token);