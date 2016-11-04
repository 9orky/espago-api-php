<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Factory\ResponseFactory;
use Gorky\Espago\Handler\AbstractResponseHandler;
use Gorky\Espago\Handler\ResponseHandlerInterface;
use Gorky\Espago\Http\HttpClient;
use Gorky\Espago\Model\ApiCredentials;

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