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
use Gorky\Espago\Model\Response\DccDecision;

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
            ->setAmount((float) $apiResponse['amount'])
            ->setCurrency($apiResponse['currency'])
            ->setState($apiResponse['state'])
            ->setClientId($apiResponse['client'])
            ->setCreatedAt((new \DateTime())->setTimestamp($apiResponse['created_at']))
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
            $charge->set3dSecure(true);
        }

        if (isset($apiResponse['reversable'])) {
            $charge->setReversable($apiResponse['reversable']);
        }

        if (isset($apiResponse['reject_reason'])) {
            $charge->setRejectReason($apiResponse['reject_reason']);
        }

        if (isset($apiResponse['multicurrency_indicator'])) {
            $charge->setMultiCurrencyIndicator($apiResponse['multicurrency_indicator']);
        }

        if (isset($apiResponse['dcc_decision_information'])) {
            $charge->setDccDecision(new DccDecision(
                $apiResponse['dcc_decision_information']['cardholder_currency_name'],
                $apiResponse['dcc_decision_information']['cardholder_amount'],
                $apiResponse['dcc_decision_information']['conversion_rate']
            ));
        }

        $this->paymentIsProcessedSuccessfullySoFar($charge);
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
    private function paymentIsProcessedSuccessfullySoFar(Charge $charge)
    {
        if (!in_array($charge->getState(), [Charge::PAYMENT_STATUS_NEW, Charge::PAYMENT_STATUS_EXECUTED])) {
            throw new PaymentOperationFailedException(
                new PaymentOperationError($charge->getIssuerResponseCode())
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