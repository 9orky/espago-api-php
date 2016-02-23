<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Api\Call\HttpCall;
use Gorky\Espago\Api\Client\HttpClient;
use Gorky\Espago\Api\Response\CreateAuthorizationResponse;
use Gorky\Espago\Api\Response\CreateChargeResponse;
use Gorky\Espago\Api\Response\CreateClientResponse;
use Gorky\Espago\Api\Response\DeleteClientResponse;
use Gorky\Espago\Api\Response\GetClientResponse;
use Gorky\Espago\Api\Response\GetTokenResponse;
use Gorky\Espago\Api\Response\UpdateClientResponse;
use Gorky\Espago\Exception\EspagoApiBadRequestException;
use Gorky\Espago\Exception\EspagoApiUnavailableException;
use Gorky\Espago\Exception\NetworkConnectionException;
use Gorky\Espago\Model\Authorization;
use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Charge;
use Gorky\Espago\Model\Client;
use Gorky\Espago\Model\Token;

class EspagoApi
{

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var
     */
    private $appId;

    /**
     * @var
     */
    private $publicKey;

    /**
     * @var
     */
    private $password;

    /**
     * @param $espagoAppId
     * @param $espagoPublicKey
     * @param $espagoPassword
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient, $espagoAppId, $espagoPublicKey, $espagoPassword)
    {
        $this->httpClient = $httpClient;
        $this->appId      = $espagoAppId;
        $this->publicKey  = $espagoPublicKey;
        $this->password   = $espagoPassword;
    }

    /**
     * @param $token
     * @return GetTokenResponse
     * @throws EspagoApiBadRequestException
     * @throws EspagoApiUnavailableException
     * @throws NetworkConnectionException
     */
    public function getToken($token)
    {
        $response = $this->httpClient->makeCall(new HttpCall(
            sprintf('/api/tokens/%s', $token),
            HttpCall::METHOD_GET,
            $this->appId,
            $this->password
        ));

        return new GetTokenResponse(
            new Token($response['id'])
        );
    }

    /**
     * @param Token $token
     * @param null $email
     * @param null $description
     * @return CreateClientResponse
     * @throws EspagoApiBadRequestException
     * @throws EspagoApiUnavailableException
     * @throws NetworkConnectionException
     */
    public function createClient(Token $token, $email = null, $description = null)
    {
        $call = (new HttpCall(
            '/api/clients',
            HttpCall::METHOD_POST,
            $this->appId,
            $this->password
        ));

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
     * @throws EspagoApiBadRequestException
     * @throws EspagoApiUnavailableException
     * @throws NetworkConnectionException
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
     * @throws EspagoApiBadRequestException
     * @throws EspagoApiUnavailableException
     * @throws NetworkConnectionException
     * @throws ApiHttpCallUnsupportedMethodException
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
     * @throws EspagoApiBadRequestException
     * @throws EspagoApiUnavailableException
     * @throws NetworkConnectionException
     */
    public function removeClient(Client $client)
    {
        $response = $this->httpClient->makeCall(new HttpCall(
            sprintf('/api/clients/%d', $client->getId()),
            HttpCall::METHOD_DELETE,
            $this->appId,
            $this->password
        ));

        return new DeleteClientResponse(204 === $response->getStatusCode());
    }

    /**
     * @param Client $client
     * @param $amount
     * @param $currency
     * @param $description
     * @return CreateAuthorizationResponse
     * @throws EspagoApiBadRequestException
     * @throws EspagoApiUnavailableException
     * @throws NetworkConnectionException
     */
    public function createAuthorization(Client $client, $amount, $currency, $description)
    {
        $call = (new HttpCall(
            sprintf('/api/clients/%s/authorize', $client->getId()),
            HttpCall::METHOD_POST,
            $this->appId,
            $this->password
        ));

        $call->setFormData([
            'client'      => $client->getId(),
            'amount'      => $amount,
            'currency'    => $currency,
            'description' => $description,
            'complete'    => false
        ]);

        $response = $this->httpClient->makeCall($call);

        return new CreateAuthorizationResponse(
            new Authorization(
                $response['id'],
                $response['email'],
                $response['description'],
                $response['deleted'],
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
     * @param $amount
     * @param $currency
     * @param $description
     * @return CreateChargeResponse
     * @throws EspagoApiBadRequestException
     * @throws EspagoApiUnavailableException
     * @throws NetworkConnectionException
     */
    public function createCharge(Client $client, $amount, $currency, $description)
    {
        $call = (new HttpCall(
            '/api/charges',
            HttpCall::METHOD_POST,
            $this->appId,
            $this->password
        ));

        $call->setFormData([
            'client'      => $client->getId(),
            'amount'      => $amount,
            'currency'    => $currency,
            'description' => $description
        ]);

        $response = $this->httpClient->makeCall($call);

        return new CreateChargeResponse(
            new Charge(
                $response['id'],
                $response['transaction_id'],
                $response['amount'],
                $response['currency'],
                $response['description'],
                $response['state'],
                $response['issuer_response_code'],
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
}