<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Exception\Api\ServiceUnavailableException;
use Gorky\Espago\Exception\Call\HttpCallUnsupportedMethodException;
use Gorky\Espago\Exception\NetworkConnectionException;
use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Handler\AbstractResponseHandler;
use Gorky\Espago\Handler\TokenResponseHandler;
use Gorky\Espago\Http\HttpClient;
use Gorky\Espago\Model\Response\Token;
use Gorky\Espago\Model\UnauthorizedCard;

class TokensApi extends AbstractApi
{
    /**
     * @param string $number
     * @param string $firstName
     * @param string $lastName
     * @param string $month
     * @param string $year
     * @param string $code
     *
     * @return UnauthorizedCard
     */
    public function createUnauthorizedCard($number, $firstName, $lastName, $month, $year, $code): UnauthorizedCard
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
     *
     * @return Token
     *
     * @throws BadRequestException
     * @throws ServiceUnavailableException
     * @throws NetworkConnectionException
     * @throws HttpCallUnsupportedMethodException
     */
    public function createToken(UnauthorizedCard $unauthorizedCard): Token
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCallAuthorizedWithPublicKey(
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

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param string $token
     *
     * @return Token
     *
     * @throws BadRequestException
     * @throws ServiceUnavailableException
     * @throws NetworkConnectionException
     */
    public function getToken(string $token): Token
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildGetCall(
                sprintf('/api/tokens/%s', $token)
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }
}