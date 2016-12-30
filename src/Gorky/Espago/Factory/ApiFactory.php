<?php

declare(strict_types = 1);

namespace Gorky\Espago\Factory;

use Gorky\Espago\Api\ChargesApi;
use Gorky\Espago\Api\ClientsApi;
use Gorky\Espago\Api\CvvTokensApi;
use Gorky\Espago\Api\TokensApi;
use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Handler\ChargeResponseHandler;
use Gorky\Espago\Handler\ClientResponseHandler;
use Gorky\Espago\Handler\CvvTokenResponseHandler;
use Gorky\Espago\Handler\TokenResponseHandler;
use Gorky\Espago\Http\HttpClient;
use Gorky\Espago\Model\ApiCredentials;

class ApiFactory
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var HttpCallFactory
     */
    protected $httpCallFactory;

    /**
     * @param string $apiUrl
     * @param ApiCredentials $apiCredentials
     */
    public function __construct(string $apiUrl, ApiCredentials $apiCredentials)
    {
        $this->httpClient = new HttpClient($apiUrl);
        $this->httpCallFactory = new HttpCallFactory($apiCredentials);
    }

    /**
     * @return TokensApi
     */
    public function getTokensApi(): TokensApi
    {
        return new TokensApi(
            $this->httpClient,
            $this->httpCallFactory,
            new TokenResponseHandler()
        );
    }

    /**
     * @return CvvTokensApi
     */
    public function getCvvTokensApi(): CvvTokensApi
    {
        return new CvvTokensApi(
            $this->httpClient,
            $this->httpCallFactory,
            new CvvTokenResponseHandler()
        );
    }

    /**
     * @return ClientsApi
     */
    public function getClientsApi(): ClientsApi
    {
        return new ClientsApi(
            $this->httpClient,
            $this->httpCallFactory,
            new ClientResponseHandler()
        );
    }

    /**
     * @return ChargesApi
     */
    public function getChargesApi(): ChargesApi
    {
        return new ChargesApi(
            $this->httpClient,
            $this->httpCallFactory,
            new ChargeResponseHandler()
        );
    }
}