<?php

namespace Gorky\Espago\Factory;

use Gorky\Espago\Api\ChargesApi;
use Gorky\Espago\Api\ClientsApi;
use Gorky\Espago\Api\TokensApi;
use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\HttpClient;
use Gorky\Espago\Value\ApiCredentials;

class ApiFactory
{
    /**
     * @var HttpCallFactory
     */
    protected $httpCallFactory;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @param HttpCallFactory $httpCallFactory
     * @param HttpClient $httpClient
     */
    public function __construct(HttpCallFactory $httpCallFactory, HttpClient $httpClient)
    {
        $this->httpCallFactory = $httpCallFactory;
        $this->httpClient = $httpClient;
    }

    /**
     * @return TokensApi
     */
    public function buildTokensApi()
    {
        return new TokensApi($this->httpCallFactory, $this->httpClient);
    }

    /**
     * @return ClientsApi
     */
    public function buildClientsApi()
    {
        return new ClientsApi($this->httpCallFactory, $this->httpClient);
    }

    /**
     * @return ChargesApi
     */
    public function buildChargesApi()
    {
        return new ChargesApi($this->httpCallFactory, $this->httpClient);
    }
}