<?php

namespace Gorky\Espago\Api;

use Gorky\Espago\Api\Response\CreateAuthorizationResponse;
use Gorky\Espago\Api\Response\CreateChargeResponse;
use Gorky\Espago\Error\PaymentOperationErrorMapper;
use Gorky\Espago\Exception\PaymentOperationFailedException;
use Gorky\Espago\Model\Authorization;
use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Charge;
use Gorky\Espago\Model\Client;
use Gorky\Espago\Model\Token;

class EspagoPaymentApi extends EspagoApi
{
    const PAYMENT_STATUS_NEW            = 'new';
    const PAYMENT_STATUS_EXECUTED       = 'executed';
    const PAYMENT_STATUS_REJECTED       = 'rejected';
    const PAYMENT_STATUS_FAILED         = 'failed';
    const PAYMENT_STATUS_TDS_REDIRECTED = 'tds_redirected';
    const PAYMENT_STATUS_DCC_DECISION   = 'dcc_decision';
    const PAYMENT_STATUS_RESIGNED       = 'resigned';

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
     * @param string $status
     * @return bool
     */
    public static function paymentHasSuccessfulStatus($status)
    {
        return self::PAYMENT_STATUS_EXECUTED === $status;
    }

    /**
     * @return array
     */
    public static function getAvailablePaymentStatuses()
    {
        return self::$availablePaymentStatuses;
    }

    /**
     * @param Client $client
     * @param $amount
     * @param $currency
     * @param $description
     * @return CreateAuthorizationResponse
     * @throws \Gorky\Espago\Exception\ApiHttpCallUnsupportedMethodException
     * @throws \Gorky\Espago\Exception\EspagoApiBadRequestException
     * @throws \Gorky\Espago\Exception\EspagoApiUnavailableException
     * @throws \Gorky\Espago\Exception\NetworkConnectionException
     * @throws \Gorky\Espago\Exception\PaymentOperationFailedException
     */
    public function createAuthorization(Client $client, $amount, $currency, $description)
    {
        $response = $this->httpClient->makeCall(
            $this->httpCallBuilder->buildPostCall(sprintf('/api/clients/%s/authorize', $client->getId()), [
                'client'      => $client->getId(),
                'amount'      => $amount,
                'currency'    => $currency,
                'description' => $description,
                'complete'    => false
            ])
        );

        $this->paymentOperationWasSuccessful($response);

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
     * @throws \Gorky\Espago\Exception\ApiHttpCallUnsupportedMethodException
     * @throws \Gorky\Espago\Exception\EspagoApiBadRequestException
     * @throws \Gorky\Espago\Exception\EspagoApiUnavailableException
     * @throws \Gorky\Espago\Exception\NetworkConnectionException
     * @throws \Gorky\Espago\Exception\PaymentOperationFailedException
     */
    public function createCharge(Client $client, $amount, $currency, $description)
    {
        $response = $this->httpClient->makeCall(
            $this->httpCallBuilder->buildPostCall('/api/charges', [
                'client'      => $client->getId(),
                'amount'      => $amount,
                'currency'    => $currency,
                'description' => $description
            ])
        );

        $this->paymentOperationWasSuccessful($response);

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

    /**
     * @param Token $token
     * @param $amount
     * @param $currency
     * @param $description
     * @return CreateChargeResponse
     * @throws PaymentOperationFailedException
     * @throws \Gorky\Espago\Exception\ApiHttpCallUnsupportedMethodException
     * @throws \Gorky\Espago\Exception\EspagoApiBadRequestException
     * @throws \Gorky\Espago\Exception\EspagoApiUnavailableException
     * @throws \Gorky\Espago\Exception\NetworkConnectionException
     */
    public function createSingleCharge(Token $token, $amount, $currency, $description)
    {
        $response = $this->httpClient->makeCall(
            $this->httpCallBuilder->buildPostCall('/api/charges', [
                'card'        => $token->getTokenValue(),
                'amount'      => $amount,
                'currency'    => $currency,
                'description' => $description
            ])
        );

        $this->paymentOperationWasSuccessful($response);

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

    /**
     * @param array $response
     * @throws PaymentOperationFailedException
     * @throws \Gorky\Espago\Exception\EspagoApiUndefinedErrorException
     */
    private function paymentOperationWasSuccessful(array $response)
    {
        if (isset($response['status']) && EspagoPaymentApi::paymentHasSuccessfulStatus($response['status'])) {
            throw new PaymentOperationFailedException(
                PaymentOperationErrorMapper::getErrorByCode($response['issuer_response_code'])
            );
        }
    }
}