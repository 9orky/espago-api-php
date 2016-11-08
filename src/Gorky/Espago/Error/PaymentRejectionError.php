<?php

declare(strict_types = 1);

namespace Gorky\Espago\Error;

use Gorky\Espago\Exception\Payment\UndefinedPaymentRejectionReasonException;

class PaymentRejectionError
{
    /**
     * @var string
     */
    private $reason;

    /**
     * @var string
     */
    private $meaning;

    /**
     * @var array
     */
    private static $paymentRejectionReasons = [
        'declined' => 'Unactivated type of service (MOTO, ecommerce), also lack of funds.',
        'card expired' => 'Card expiration date was exceeded.',
        'invalid amount' => 'It refers to the transactions amount limit or number of transactions limit.',
        'invalid card' => '	Invalid card number, card doesn\'t exist.',
        'invalid profile' => 'Invalid MID account',
        'referral A / pick up card' => 'Card marked as stolen/lost/blocked. IMPORTANT NOTICE: It is forbidden to retry transactions that ended with this reason. It may be recognized as fraud attempt!',
        'referral B' => '	Transaction needs confirmation.',
        'serv not allowed' => 'Merchant does not support this particular type of a card',
        '3ds_not_authorized' => 'Rejected during 3D-Secure',
    ];

    /**
     * @param string $reason
     *
     * @throws UndefinedPaymentRejectionReasonException
     */
    public function __construct(string $reason)
    {
        if (!in_array($reason, array_keys(self::$paymentRejectionReasons))) {
            throw new UndefinedPaymentRejectionReasonException(sprintf('Unknown reason: %s', $reason));
        }

        $this->reason = $reason;
        $this->meaning = self::$paymentRejectionReasons[$reason];
    }

    /**
     * @param string $reason
     *
     * @return PaymentRejectionError
     */
    public static function create(string $reason): PaymentRejectionError
    {
        return new self($reason);
    }
}