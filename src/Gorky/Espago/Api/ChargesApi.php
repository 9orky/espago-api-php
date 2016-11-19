<?php

declare(strict_types = 1);

namespace Gorky\Espago\Api;

use Gorky\Espago\Error\BadRequestError;
use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Model\Response\Charge;
use Gorky\Espago\Model\Response\Client;
use Gorky\Espago\Model\Response\Token;
use GuzzleHttp\Exception\ClientException;

class ChargesApi extends AbstractApi
{
    /**
     * @param Token $token
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return Charge
     */
    public function createAuthorizationByToken(Token $token, float $amount, string $currency, string $description): Charge
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                '/api/charges', [
                    'card'        => $token->getTokenValue(),
                    'amount'      => $amount,
                    'currency'    => $currency,
                    'description' => $description,
                    'complete'    => false
                ]
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param Client $client
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return Charge
     */
    public function createAuthorizationByClient(Client $client, float $amount, string $currency, string $description): Charge
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                '/api/charges', [
                    'client'      => $client->getId(),
                    'amount'      => $amount,
                    'currency'    => $currency,
                    'description' => $description,
                    'complete'    => false
                ]
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param string $chargeId
     * @param float $amount
     *
     * @return Charge
     */
    public function captureAuthorization(string $chargeId, float $amount): Charge
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                sprintf('/api/charges/%s/complete', $chargeId),
                [
                    'amount' => $amount
                ]
            ),
            function(ClientException $e) {
                $badRequestException = new BadRequestException();
                foreach (json_decode($e->getResponse()->getBody(), true)['errors'] as $field => $messages) {
                    foreach ($messages as $message) {
                        $badRequestException->addBadRequestError(BadRequestError::create($field, $message));
                    }
                }

                throw $badRequestException;
            }
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param Token $token
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return Charge
     */
    public function createChargeByToken(Token $token, float $amount, string $currency, string $description): Charge
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                '/api/charges', [
                    'card'        => $token->getTokenValue(),
                    'amount'      => $amount,
                    'currency'    => $currency,
                    'description' => $description
                ]
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param Client $client
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return Charge
     */
    public function createChargeByClient(Client $client, float $amount, string $currency, string $description): Charge
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                '/api/charges', [
                    'client'      => $client->getId(),
                    'amount'      => $amount,
                    'currency'    => $currency,
                    'description' => $description
                ]
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param string $chargeId
     * @param bool $acceptDccExchangeRate
     *
     * @return Charge
     */
    public function makeDccDecision(string $chargeId, bool $acceptDccExchangeRate)
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                sprintf('/api/charges/%s/dcc_decision', $chargeId), [
                    'decision' => $acceptDccExchangeRate ? 'Y' : 'N'
                ]
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param string $chargeId
     *
     * @return Charge
     */
    public function getCharge(string $chargeId): Charge
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildGetCall(
                sprintf('/api/charges/%s', $chargeId)
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param string $chargeId
     * @param float $amount
     *
     * @return Charge
     */
    public function refundCharge(string $chargeId, float $amount): Charge
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                sprintf('/api/charges/%s/refund', $chargeId),
                [
                    'amount' => $amount
                ]
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param string $chargeId
     *
     * @return Charge
     */
    public function cancelCharge(string $chargeId): Charge
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildDeleteCall(
                sprintf('/api/charges/%s', $chargeId)
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }
}