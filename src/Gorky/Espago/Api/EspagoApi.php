<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Api\Client\HttpClient;
use Gorky\Espago\Api\Response\GetTokenResponse;
use Gorky\Espago\Builder\HttpCallBuilder;
use Gorky\Espago\Exception\EspagoApiBadRequestException;
use Gorky\Espago\Exception\EspagoApiUnavailableException;
use Gorky\Espago\Exception\NetworkConnectionException;
use Gorky\Espago\Value\Token;
use Gorky\Espago\Model\UnauthorizedCard;
use Gorky\Espago\Value\ApiCredentials;
use Gorky\Espago\Value\HttpAuth;

class EspagoApi
{
    /**
     * @var ApiCredentials
     */
    protected $apiCredentials;

    /**
     * @var HttpCallBuilder
     */
    protected $httpCallBuilder;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @param ApiCredentials $apiCredentials
     * @param HttpCallBuilder $httpCallBuilder
     * @param HttpClient $httpClient
     */
    public function __construct(ApiCredentials $apiCredentials, HttpCallBuilder $httpCallBuilder, HttpClient $httpClient)
    {
        $this->apiCredentials = $apiCredentials;
        $this->httpCallBuilder = $httpCallBuilder;
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $number
     * @param string $firstName
     * @param string $lastName
     * @param string $month
     * @param string $year
     * @param string $code
     * @return UnauthorizedCard
     */
    public function createUnauthorizedCard($number, $firstName, $lastName, $month, $year, $code)
    {
        return new UnauthorizedCard(
            $number,
            $firstName,
            $lastName,
            $month,
            $year,
            $code
        );
    }

    /**
     * @param UnauthorizedCard $unauthorizedCard
     * @return GetTokenResponse
     * @throws EspagoApiBadRequestException
     * @throws EspagoApiUnavailableException
     * @throws NetworkConnectionException
     * @throws \Gorky\Espago\Exception\ApiHttpCallUnsupportedMethodException
     */
    public function createToken(UnauthorizedCard $unauthorizedCard)
    {
        $response = $this->httpClient->makeCall(
            $this->httpCallBuilder->buildPostCall(
                new HttpAuth(
                    'publickey',
                    $this->apiCredentials->getPublicKey()
                ),
                '/api/tokens',
                [
                    'card[first_name]'         => $unauthorizedCard->getFirstName(),
                    'card[last_name]'          => $unauthorizedCard->getLastName(),
                    'card[number]'             => $unauthorizedCard->getNumber(),
                    'card[year]'               => $unauthorizedCard->getYear(),
                    'card[month]'              => $unauthorizedCard->getMonth(),
                    'card[verification_value]' => $unauthorizedCard->getCode(),
                ]
            )
        );

        return new GetTokenResponse(
            new Token($response['id'])
        );
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
        $response = $this->httpClient->makeCall(
            $this->httpCallBuilder->buildGetCall(
                new HttpAuth(
                    $this->apiCredentials->getAppId(),
                    $this->apiCredentials->getPassword()
                ),
                sprintf('/api/tokens/%s', $token)
            )
        );

        return new GetTokenResponse(
            new Token($response['id'])
        );
    }
}