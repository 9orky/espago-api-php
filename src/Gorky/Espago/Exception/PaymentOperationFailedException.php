<?php

namespace Gorky\Espago\Exception;

use Gorky\Espago\Error\PaymentOperationError;

class PaymentOperationFailedException extends \Exception
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