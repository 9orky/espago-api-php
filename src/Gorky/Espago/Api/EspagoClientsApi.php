<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Api\Call\HttpCall;
use Gorky\Espago\Api\Response\CreateClientResponse;
use Gorky\Espago\Api\Response\DeleteClientResponse;
use Gorky\Espago\Api\Response\GetClientResponse;
use Gorky\Espago\Api\Response\UpdateClientResponse;
use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Client;
use Gorky\Espago\Model\Token;

class EspagoClientsApi extends EspagoApi
{

    /**
     * @param Token $token
     * @param null $email
     * @param null $description
     * @return CreateClientResponse
     * @throws \Gorky\Espago\Exception\ApiHttpCallUnsupportedMethodException
     * @throws \Gorky\Espago\Exception\EspagoApiBadRequestException
     * @throws \Gorky\Espago\Exception\EspagoApiUnavailableException
     * @throws \Gorky\Espago\Exception\NetworkConnectionException
     * @throws \Gorky\Espago\Exception\PaymentOperationFailedException
     */
    public function createClient(Token $token, $email = null, $description = null)
    {
        $call = new HttpCall(
            '/api/clients',
            HttpCall::METHOD_POST,
            $this->appId,
            $this->password
        );

        $call->setFormData([
            'email'       => $email,
            'card'        => $token->getTokenValue(),
            'description' => $description
        ]);

        $response = $this->httpClient->makeCall($call);

        return new CreateClientResponse(
            new Client(
                $response['id'],
                $response['email'],
                $response['description'],
                $response['created_at']
            ),
            new Card(
                $response['card']['company'],
                $response['card']['last4'],
                $response['card']['first_name'],
                $response['card']['last_name'],
                $response['card']['year'],
                $response['card']['month'],
                $response['card']['created_at']
            )
        );
    }

    /**
     * @param $clientId
     * @return GetClientResponse
     * @throws \Gorky\Espago\Exception\ApiHttpCallUnsupportedMethodException
     * @throws \Gorky\Espago\Exception\EspagoApiBadRequestException
     * @throws \Gorky\Espago\Exception\EspagoApiUnavailableException
     * @throws \Gorky\Espago\Exception\NetworkConnectionException
     * @throws \Gorky\Espago\Exception\PaymentOperationFailedException
     */
    public function getClient($clientId)
    {
        $response = $this->httpClient->makeCall(new HttpCall(
            sprintf('/api/clients/%s', $clientId),
            HttpCall::METHOD_GET,
            $this->appId,
            $this->password
        ));

        return new GetClientResponse(
            new Client(
                $response['id'],
                $response['email'],
                $response['description'],
                $response['created_at']
            ),
            new Card(
                $response['card']['company'],
                $response['card']['last4'],
                $response['card']['first_name'],
                $response['card']['last_name'],
                $response['card']['year'],
                $response['card']['month'],
                $response['card']['created_at']
            )
        );
    }

    /**
     * @param Client $client
     * @param Token $token
     * @param null $description
     * @param null $email
     * @return UpdateClientResponse
     * @throws \Gorky\Espago\Exception\ApiHttpCallUnsupportedMethodException
     * @throws \Gorky\Espago\Exception\EspagoApiBadRequestException
     * @throws \Gorky\Espago\Exception\EspagoApiUnavailableException
     * @throws \Gorky\Espago\Exception\NetworkConnectionException
     * @throws \Gorky\Espago\Exception\PaymentOperationFailedException
     */
    public function updateClient(Client $client, Token $token, $description = null, $email = null)
    {
        $call = (new HttpCall(
            sprintf('/api/clients/%s', $client->getId()),
            HttpCall::METHOD_PUT,
            $this->appId,
            $this->password
        ));

        $call->setFormData([
            'email'       => $email,
            'card'        => $token->getTokenValue(),
            'description' => $description
        ]);

        $response = $this->httpClient->makeCall($call);

        return new UpdateClientResponse(
            new Client(
                $response['id'],
                $response['email'],
                $response['description'],
                $response['created_at']
            ),
            new Card(
                $response['card']['company'],
                $response['card']['last4'],
                $response['card']['first_name'],
                $response['card']['last_name'],
                $response['card']['year'],
                $response['card']['month'],
                $response['card']['created_at']
            )
        );
    }

    /**
     * @param Client $client
     * @return DeleteClientResponse
     * @throws \Gorky\Espago\Exception\ApiHttpCallUnsupportedMethodException
     * @throws \Gorky\Espago\Exception\EspagoApiBadRequestException
     * @throws \Gorky\Espago\Exception\EspagoApiUnavailableException
     * @throws \Gorky\Espago\Exception\NetworkConnectionException
     * @throws \Gorky\Espago\Exception\PaymentOperationFailedException
     */
    public function removeClient(Client $client)
    {
        $response = $this->httpClient->makeCall(new HttpCall(
            sprintf('/api/clients/%d', $client->getId()),
            HttpCall::METHOD_DELETE,
            $this->appId,
            $this->password
        ));

        return new DeleteClientResponse(204 === $response);
    }
}