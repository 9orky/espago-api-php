<?php

declare(strict_types = 1);

namespace Gorky\Espago\Exception\Payment;

use Gorky\Espago\Error\PaymentRejectionError;
use Gorky\Espago\Exception\EspagoException;

class PaymentRejectedException extends EspagoException
{
    /**
     * @var PaymentRejectionError
     */
    private $paymentRejectionError;

    /**
     * @param PaymentRejectionError $paymentRejectionError
     */
    public function setPaymentRejectionError(PaymentRejectionError $paymentRejectionError)
    {
        // immutable
        if (null === $this->paymentRejectionError) {
            $this->paymentRejectionError = $paymentRejectionError;
        }
    }

    /**
     * @return PaymentRejectionError
     */
    public function getPaymentRejectionError(): PaymentRejectionError
    {
        return $this->paymentRejectionError;
    }
}