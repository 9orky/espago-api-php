<?php

declare(strict_types = 1);

namespace Gorky\Espago\Api;

use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Exception\Api\ServiceUnavailableException;
use Gorky\Espago\Exception\Call\HttpCallUnsupportedMethodException;
use Gorky\Espago\Exception\Transport\NetworkConnectionException;
use Gorky\Espago\Exception\Payment\PaymentOperationFailedException;
use Gorky\Espago\Model\Response\Client;
use Gorky\Espago\Model\Response\Token;

class ClientsApi extends AbstractApi
{
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
    public function createClient(Token $token, string $email = null, string $description = null): Client
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                '/api/clients',
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
    public function getClient(string $clientId): Client
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
     * @param string|null $description
     * @param string|null $email
     *
     * @return Client
     *
     * @throws HttpCallUnsupportedMethodException
     * @throws BadRequestException
     * @throws ServiceUnavailableException
     * @throws NetworkConnectionException
     * @throws PaymentOperationFailedException
     */
    public function updateClient(Client $client, Token $token, string $description = null, string $email = null): Client
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
    public function removeClient(Client $client): bool
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildDeleteCall(
                sprintf('/api/clients/%d', $client->getId())
            )
        );

        return 204 === $apiResponse->getStatusCode();
    }
}