<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Error\BadRequestError;
use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Factory\HttpCallFactory;
use Gorky\Espago\Handler\ChargeResponseHandler;
use Gorky\Espago\Http\HttpClient;
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
    public function createAuthorizationByToken(Token $token, $amount, $currency, $description)
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

        $charge = $this->responseHandler->handle($apiResponse);

        $this->responseHandler->issuerResponseCodeIsValid($charge);
        $this->responseHandler->chargeIsNotRejected($charge);

        return $charge;
    }

    /**
     * @param Client $client
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return Charge
     */
    public function createAuthorizationByClient(Client $client, $amount, $currency, $description)
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

        $charge = $this->responseHandler->handle($apiResponse);

        $this->responseHandler->issuerResponseCodeIsValid($charge);
        $this->responseHandler->chargeIsNotRejected($charge);

        return $charge;
    }

    /**
     * @param string $chargeId
     * @param float $amount
     *
     * @return Charge
     */
    public function captureAuthorization($chargeId, $amount)
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

        $charge = $this->responseHandler->handle($apiResponse);

        $this->responseHandler->issuerResponseCodeIsValid($charge);
        $this->responseHandler->chargeIsNotRejected($charge);

        return $charge;
    }

    /**
     * @param Token $token
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return Charge
     */
    public function createChargeByToken(Token $token, $amount, $currency, $description)
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

        $charge = $this->responseHandler->handle($apiResponse);

        $this->responseHandler->issuerResponseCodeIsValid($charge);
        $this->responseHandler->chargeIsNotRejected($charge);

        return $charge;
    }

    /**
     * @param Client $client
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return Charge
     */
    public function createChargeByClient(Client $client, $amount, $currency, $description)
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

        $charge = $this->responseHandler->handle($apiResponse);

        $this->responseHandler->issuerResponseCodeIsValid($charge);
        $this->responseHandler->chargeIsNotRejected($charge);

        return $charge;
    }

    /**
     * @param string $chargeId
     *
     * @return Charge
     */
    public function getCharge($chargeId)
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildGetCall(
                sprintf('/api/charges/%s', $chargeId)
            )
        );

        $charge = $this->responseHandler->handle($apiResponse);

        $this->responseHandler->issuerResponseCodeIsValid($charge);
        $this->responseHandler->chargeIsNotRejected($charge);

        return $charge;
    }

    /**
     * @param string $chargeId
     *
     * @return Charge
     */
    public function refundCharge($chargeId)
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCall(
                sprintf('/api/charges/%s/refund', $chargeId)
            )
        );

        $charge = $this->responseHandler->handle($apiResponse);

        $this->responseHandler->issuerResponseCodeIsValid($charge);
        $this->responseHandler->chargeIsNotRejected($charge);

        return $charge;
    }

    /**
     * @param string $chargeId
     *
     * @return Charge
     */
    public function cancelCharge($chargeId)
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildDeleteCall(
                sprintf('/api/charges/%s', $chargeId)
            )
        );

        $charge = $this->responseHandler->handle($apiResponse);

        $this->responseHandler->issuerResponseCodeIsValid($charge);
        $this->responseHandler->chargeIsNotRejected($charge);

        return $charge;
    }
}