<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Error\BadRequestError;
use Gorky\Espago\Error\PaymentOperationError;
use Gorky\Espago\Error\PaymentRejectionError;
use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Exception\Payment\PaymentOperationFailedException;
use Gorky\Espago\Exception\Payment\PaymentRejectedException;
use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Client;
use Gorky\Espago\Response\Charge\CancelCharge;
use Gorky\Espago\Response\ChargeResponse;
use Gorky\Espago\Response\Charge\CaptureAuthorization;
use Gorky\Espago\Response\Charge\CreateAuthorization;
use Gorky\Espago\Response\Charge\CreateCharge;
use Gorky\Espago\Response\Charge\GetCharge;
use Gorky\Espago\Value\Token;
use GuzzleHttp\Exception\ClientException;

class ChargesApi extends AbstractApi
{
    /**
     * New payment, client's account has not been charged.
     */
    const PAYMENT_STATUS_NEW = 'new';

    /**
     * Payment executed, client's account was successfully charged.
     */
    const PAYMENT_STATUS_EXECUTED = 'executed';

    /**
     * In response of the rejected transaction, you receive reject_reason parameter.
     */
    const PAYMENT_STATUS_REJECTED = 'rejected';

    /**
     * Payment ended by failure
     */
    const PAYMENT_STATUS_FAILED = 'failed';

    /**
     * State available only if 3D-Secure is enabled. Customer is redirected to 3D-Secure page (bank/issuer site),
     * Espago gateway is waiting for returning customer.
     */
    const PAYMENT_STATUS_TDS_REDIRECTED = 'tds_redirected';

    /**
     * [State available only if DCC is enabled]. Waiting for sending by Merchant the decision,
     * about the payment currency chosen by customer.
     */
    const PAYMENT_STATUS_DCC_DECISION = 'dcc_decision';

    /**
     * Customer resigned from the autorization of payment or left payment [state available if enabled is 3D-Secure,
     * DCC and/or MasterPass]. In case of leaving transaction with state "new", "tds_redirected" or "dcc_decision"
     * (no customer action during 1,5 hour) transactions will change state to "resigned".
     */
    const PAYMENT_STATUS_RESIGNED = 'resigned';

    /**
     * @var array
     */
    private static $availablePaymentStatuses = [
        self::PAYMENT_STATUS_NEW,
        self::PAYMENT_STATUS_EXECUTED,
        self::PAYMENT_STATUS_REJECTED,
        self::PAYMENT_STATUS_FAILED,
        self::PAYMENT_STATUS_TDS_REDIRECTED,
        self::PAYMENT_STATUS_DCC_DECISION,
        self::PAYMENT_STATUS_RESIGNED
    ];

    /**
     * @return array
     */
    public static function getAvailablePaymentStatuses()
    {
        return self::$availablePaymentStatuses;
    }

    /**
     * @param Token $token
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return CreateAuthorization
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

        $response = new CreateAuthorization($apiResponse['id']);

        $this->mutateChargeResponse($response, $this->buildCard($apiResponse), $apiResponse);
        $this->issuerResponseMustBeValid($response);
        $this->chargeCannotBeRejected($response);

        return $response;
    }

    /**
     * @param Client $client
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return CreateAuthorization
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

        $response = new CreateAuthorization($apiResponse['id']);

        $this->mutateChargeResponse($response, $this->buildCard($apiResponse), $apiResponse);
        $this->issuerResponseMustBeValid($response);
        $this->chargeCannotBeRejected($response);

        return $response;
    }

    /**
     * @param string $chargeId
     * @param float $amount
     *
     * @return CaptureAuthorization
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

        $response = new CaptureAuthorization($apiResponse['id']);

        $this->mutateChargeResponse($response, $this->buildCard($apiResponse), $apiResponse);
        $this->issuerResponseMustBeValid($response);
        $this->chargeCannotBeRejected($response);

        return $response;
    }

    /**
     * @param Token $token
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return CreateCharge
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

        $response = new CreateCharge($apiResponse['id']);

        $this->mutateChargeResponse($response, $this->buildCard($apiResponse), $apiResponse);
        $this->issuerResponseMustBeValid($response);
        $this->chargeCannotBeRejected($response);

        return $response;
    }

    /**
     * @param Client $client
     * @param float $amount
     * @param string $currency
     * @param string $description
     *
     * @return CreateCharge
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

        $response = new CreateCharge($apiResponse['id']);

        $this->mutateChargeResponse($response, $this->buildCard($apiResponse), $apiResponse);
        $this->issuerResponseMustBeValid($response);
        $this->chargeCannotBeRejected($response);

        return $response;
    }

    /**
     * @param string $chargeId
     *
     * @return GetCharge
     */
    public function getCharge($chargeId)
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildGetCall(
                sprintf('/api/charges/%s', $chargeId)
            )
        );

        $response = new GetCharge($apiResponse['id']);

        $this->mutateChargeResponse($response, $this->buildCard($apiResponse), $apiResponse);

        return $response;
    }

    /**
     * @param string $chargeId
     *
     * @return CancelCharge
     */
    public function cancelCharge($chargeId)
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildDeleteCall(
                sprintf('/api/charges/%s', $chargeId)
            )
        );

        $response = new CancelCharge($apiResponse['id']);

        $this->mutateChargeResponse($response, $this->buildCard($apiResponse), $apiResponse);

        return $response;
    }

    /**
     * @param array $apiResponse
     *
     * @return Card
     */
    private function buildCard($apiResponse)
    {
        if (!isset($apiResponse['card'])) {
            return null;
        }

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

    /**
     * @param ChargeResponse $chargeResponse
     * @param Card $card
     * @param array $apiResponse
     */
    private function mutateChargeResponse(ChargeResponse $chargeResponse, Card $card = null, $apiResponse)
    {
        $chargeResponse
            ->setDescription($apiResponse['description'])
            ->setChannel($apiResponse['channel'])
            ->setAmount($apiResponse['amount'])
            ->setCurrency($apiResponse['currency'])
            ->setState($apiResponse['state'])
            ->setClient($apiResponse['client'])
            ->setCreatedAt($apiResponse['created_at'])
            ->setTransactionId($apiResponse['transaction_id'])
            ->setIssuerResponseCode($apiResponse['issuer_response_code']);

        if (null !== $card) {
            $chargeResponse->setCard($card);
        }

        if (isset($apiResponse['redirect_url'])) {
            $chargeResponse->setRedirectUrl($apiResponse['redirect_url']);
            $chargeResponse->setIs3dSecure(true);
        }

        if (isset($apiResponse['reversable'])) {
            $chargeResponse->setReversable($apiResponse['reversable']);
        }

        if (isset($apiResponse['reject_reason'])) {
            $chargeResponse->setRejectReason($apiResponse['reject_reason']);
        }
    }

    /**
     * @param ChargeResponse $chargeResponse
     *
     * @throws PaymentOperationFailedException
     */
    private function issuerResponseMustBeValid(ChargeResponse $chargeResponse)
    {
        if ('00' !== $chargeResponse->getIssuerResponseCode()) {
            throw new PaymentOperationFailedException(
                PaymentOperationError::create($chargeResponse->getIssuerResponseCode())
            );
        }
    }

    /**
     * @param ChargeResponse $chargeResponse
     *
     * @throws PaymentRejectedException
     */
    private function chargeCannotBeRejected(ChargeResponse $chargeResponse)
    {
        if (self::PAYMENT_STATUS_REJECTED === $chargeResponse->getState() && $chargeResponse->getRejectReason()) {
            $rejectReason = PaymentRejectionError::create($chargeResponse->getRejectReason());

            $paymentRejectedException = new PaymentRejectedException();
            $paymentRejectedException->setPaymentRejectionError($rejectReason);

            throw $paymentRejectedException;
        }
    }
}