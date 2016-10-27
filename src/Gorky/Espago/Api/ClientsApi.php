<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Exception\Api\ServiceUnavailableException;
use Gorky\Espago\Exception\Call\HttpCallUnsupportedMethodException;
use Gorky\Espago\Exception\NetworkConnectionException;
use Gorky\Espago\Exception\Payment\PaymentOperationFailedException;
use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Handler\ClientResponseHandler;
use Gorky\Espago\Http\HttpClient;
use Gorky\Espago\Model\Client;
use Gorky\Espago\Value\Token;

class ClientsApi extends AbstractApi
{
    /**
     * @param HttpClient $httpClient
     * @param HttpCallFactory $httpCallFactory
     * @param ClientResponseHandler $clientResponseHandler
     */
    public function __construct(
        HttpClient $httpClient,
        HttpCallFactory $httpCallFactory,
        ClientResponseHandler $clientResponseHandler
    )
    {
        parent::__construct($httpClient, $httpCallFactory);

        $this->responseHandler = $clientResponseHandler;
    }

    /**
     * @param Token $token
     * @param string|null $email
     * @param string|null $description
     *
     * @return Client
     *
     * @throws HttpCallUnsupportedMethodException
     * @throws BadRequestException
     * @throws ServiceUnavailableException
     * @throws NetworkConnectionException
     * @throws PaymentOperationFailedException
     */
    public function createClient(Token $token, $email = null, $description = null)
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                '/api/clients',
                [
                    'email'       => $email,
                    'card'        => (string) $token,
                    'description' => $description
                ]
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param string $clientId
     *
     * @return Client
     *
     * @throws HttpCallUnsupportedMethodException
     * @throws BadRequestException
     * @throws ServiceUnavailableException
     * @throws NetworkConnectionException
     * @throws PaymentOperationFailedException
     */
    public function getClient($clientId)
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildGetCall(
                sprintf('/api/clients/%s', $clientId)
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param Client $client
     * @param Token $token
     * @param null $description
     * @param null $email
     *
     * @return Client
     *
     * @throws HttpCallUnsupportedMethodException
     * @throws BadRequestException
     * @throws ServiceUnavailableException
     * @throws NetworkConnectionException
     * @throws PaymentOperationFailedException
     */
    public function updateClient(Client $client, Token $token, $description = null, $email = null)
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPutCall(
                sprintf('/api/clients/%s', $client->getId()),
                [
                    'email'       => $email,
                    'card'        => $token->getTokenValue(),
                    'description' => $description
                ]
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param Client $client
     *
     * @return bool
     *
     * @throws HttpCallUnsupportedMethodException
     * @throws BadRequestException
     * @throws ServiceUnavailableException
     * @throws NetworkConnectionException
     * @throws PaymentOperationFailedException
     */
    public function removeClient(Client $client)
    {
        $response = $this->httpClient->makeCall(
            $this->httpCallFactory->buildDeleteCall(
                sprintf('/api/clients/%d', $client->getId())
            )
        );

        return 204 === $response;
    }
}