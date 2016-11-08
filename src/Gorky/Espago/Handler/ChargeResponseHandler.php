<?php

declare(strict_types = 1);

namespace Gorky\Espago\Handler;

use Gorky\Espago\Error\PaymentOperationError;
use Gorky\Espago\Error\PaymentRejectionError;
use Gorky\Espago\Exception\Payment\PaymentOperationFailedException;
use Gorky\Espago\Exception\Payment\PaymentRejectedException;
use Gorky\Espago\Http\HttpResponse;
use Gorky\Espago\Model\Response\Card;
use Gorky\Espago\Model\Response\Charge;

class ChargeResponseHandler extends AbstractResponseHandler
{
    /**
     * @param HttpResponse $httpResponse
     *
     * @throws PaymentOperationFailedException
     * @throws PaymentRejectedException
     *
     * @return Charge
     */
    public function handle(HttpResponse $httpResponse): Charge
    {
        $apiResponse = $httpResponse->getData();

        $charge = (new Charge($apiResponse['id']))
            ->setDescription($apiResponse['description'])
            ->setChannel($apiResponse['channel'])
            ->setAmount($apiResponse['amount'])
            ->setCurrency($apiResponse['currency'])
            ->setState($apiResponse['state'])
            ->setClientId($apiResponse['client'])
            ->setCreatedAt($apiResponse['created_at'])
            ->setTransactionId($apiResponse['transaction_id'])
            ->setIssuerResponseCode($apiResponse['issuer_response_code']);

        if (isset($apiResponse['card']) && null !== $apiResponse['card']) {
            $charge->setCard(new Card(
                $apiResponse['card']['company'],
                $apiResponse['card']['last4'],
                $apiResponse['card']['first_name'],
                $apiResponse['card']['last_name'],
                $apiResponse['card']['year'],
                $apiResponse['card']['month'],
                $apiResponse['card']['created_at']
            ));
        }

        if (isset($apiResponse['redirect_url'])) {
            $charge->setRedirectUrl($apiResponse['redirect_url']);
            $charge->setIs3dSecure(true);
        }

        if (isset($apiResponse['reversable'])) {
            $charge->setReversable($apiResponse['reversable']);
        }

        if (isset($apiResponse['reject_reason'])) {
            $charge->setRejectReason($apiResponse['reject_reason']);
        }

        $this->issuerResponseCodeIsValid($charge);
        $this->chargeIsNotRejected($charge);

        return $charge;
    }

    /**
     * @param Charge $charge
     *
     * @return null
     *
     * @throws PaymentOperationFailedException
     */
    private function issuerResponseCodeIsValid(Charge $charge)
    {
        if ('00' !== $charge->getIssuerResponseCode()) {
            throw new PaymentOperationFailedException(
                PaymentOperationError::create($charge->getIssuerResponseCode())
            );
        }
    }

    /**
     * @param Charge $charge
     *
     * @return null
     *
     * @throws PaymentRejectedException
     */
    private function chargeIsNotRejected(Charge $charge)
    {
        if (Charge::PAYMENT_STATUS_REJECTED === $charge->getState() && $charge->getRejectReason()) {
            $rejectReason = PaymentRejectionError::create($charge->getRejectReason());

            $paymentRejectedException = new PaymentRejectedException();
            $paymentRejectedException->setPaymentRejectionError($rejectReason);

            throw $paymentRejectedException;
        }
    }
}