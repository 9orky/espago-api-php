<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\HttpClient;
use Gorky\Espago\Value\ApiCredentials;

abstract class AbstractApi
{
    /**
     * @var HttpCallFactory
     */
    protected $httpCallFactory;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**s
     * @param HttpCallFactory $httpCallProvider
     * @param HttpClient $httpClient
     */
    public function __construct(HttpCallFactory $httpCallProvider, HttpClient $httpClient)
    {
        $this->httpCallFactory = $httpCallProvider;
        $this->httpClient = $httpClient;
    }
}