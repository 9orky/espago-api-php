<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Handler\AbstractResponseHandler;
use Gorky\Espago\Http\HttpClient;

abstract class AbstractApi
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
     * @var AbstractResponseHandler
     */
    protected $responseHandler;

    /**
     * @param HttpClient $httpClient
     * @param HttpCallFactory $httpCallFactory
     * @param AbstractResponseHandler $responseHandler
     */
    public function __construct(
        HttpClient $httpClient,
        HttpCallFactory $httpCallFactory,
        AbstractResponseHandler $responseHandler
    ) {
        $this->httpClient = $httpClient;
        $this->httpCallFactory = $httpCallFactory;
        $this->responseHandler = $responseHandler;
    }
}