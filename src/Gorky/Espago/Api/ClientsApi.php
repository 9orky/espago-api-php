<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Exception\Api\ServiceUnavailableException;
use Gorky\Espago\Exception\Call\HttpCallUnsupportedMethodException;
use Gorky\Espago\Exception\NetworkConnectionException;
use Gorky\Espago\Exception\Payment\PaymentOperationFailedException;
use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Client;
use Gorky\Espago\Response\Client\CreateClient;
use Gorky\Espago\Response\Client\DeleteClient;
use Gorky\Espago\Response\Client\GetClient;
use Gorky\Espago\Response\Client\UpdateClient;
use Gorky\Espago\Value\Token;

class ClientsApi extends AbstractApi
{
    /**
     * @param Token $token
     * @param string|null $email
     * @param string|null $description
     *
     * @return CreateClient
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

        return new CreateClient(
            $this->buildClient($apiResponse),
            $this->buildCard($apiResponse)
        );
    }

    /**
     * @param string $clientId
     *
     * @return GetClient
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

        return new GetClient(
            $this->buildClient($apiResponse),
            $this->buildCard($apiResponse)
        );
    }

    /**
     * @param Client $client
     * @param Token $token
     * @param null $description
     * @param null $email
     *
     * @return UpdateClient
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

        return new UpdateClient(
            $this->buildClient($apiResponse),
            $this->buildCard($apiResponse)
        );
    }

    /**
     * @param Client $client
     *
     * @return DeleteClient
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

        return new DeleteClient(204 === $response);
    }

    /**
     * @param array $apiResponse
     *
     * @return Client
     */
    private function buildClient(array $apiResponse)
    {
        return new Client(
            $apiResponse['id'],
            $apiResponse['email'],
            $apiResponse['description'],
            $apiResponse['created_at']
        );
    }

    /**
     * @param array $apiResponse
     *
     * @return Card
     */
    private function buildCard(array $apiResponse)
    {
        return new Card(
            $apiResponse['card']['company'],
            $apiResponse['card']['last4'],
            $apiResponse['card']['first_name'],
            $apiResponse['card']['last_name'],
            $apiResponse['card']['year'],
            $apiResponse['card']['month'],
            $apiResponse['card']['created_at']
        );
    }
}