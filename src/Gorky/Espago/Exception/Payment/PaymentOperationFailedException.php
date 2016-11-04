<?php

namespace Gorky\Espago\Exception\Payment;

use Gorky\Espago\Error\PaymentOperationError;
use Gorky\Espago\Exception\EspagoException;

class PaymentOperationFailedException extends EspagoException
{
    /**
     * @var PaymentOperationError
     */
    private $paymentOperationError;

    /**
     * @param PaymentOperationError $paymentOperationError
     */
    public function __construct(PaymentOperationError $paymentOperationError)
    {
        parent::__construct($paymentOperationError->getMessage(), (int) $paymentOperationError->getCode());

        $this->paymentOperationError = $paymentOperationError;
    }

    /**
     * @return PaymentOperationError
     */
    public function getTransactionError()
    {
        return $this->paymentOperationError;
    }
}