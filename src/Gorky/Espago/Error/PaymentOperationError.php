<?php

declare(strict_types = 1);

namespace Gorky\Espago\Error;

use Gorky\Espago\Exception\Payment\UndefinedPaymentErrorException;

class PaymentOperationError
{
    /**
     * @var array
     */
    private static $mapping = [
        [
            'codes'     => ['00'],
            'sys-msg'   => 'Payment successful',
            'message'   => 'Your payment was successful - thank you'
        ],

        [
            'codes'     => ['01', '02'],
            'sys-msg'   => 'Voice referral required',
            'message'   => 'Operation failed - please contact your bank'
        ],

        [
            'codes'     => ['03'],
            'sys-msg'   => 'Wrong acceptant data',
            'message'   => 'Operation failed - please contact your bank'
        ],

        [
            'codes'     => ['04', '07'],
            'sys-msg'   => 'Card is blocked (not stolen or lost)',
            'message'   => 'Your card is blocked - please contact your bank. Do not try to charge this card again due to fraud attempt suspicions'
        ],

        [
            'codes'     => ['05', '13', '57', '61'],
            'sys-msg'   => 'Internet payments are inactive on this card',
            'message'   => 'Your card was declined - check you account settings in case of internet payments'
        ],

        [
            'codes'     => ['12'],
            'sys-msg'   => 'Wrong transaction',
            'message'   => 'Transaction unavailable for this card - please contact your bank'
        ],

        [
            'codes'     => ['14'],
            'sys-msg'   => 'Wrong card number',
            'message'   => 'Wrong card number - please check the data you typed in and try again'
        ],

        [
            'codes'     => ['30'],
            'sys-msg'   => 'Wrong format of authorizing message',
            'message'   => 'Operation failed - please contact you bank'
        ],

        [
            'codes'     => ['41'],
            'sys-msg'   => 'Card is reported as lost',
            'message'   => 'Your card is reported as lost. Do not try to charge this card again due to fraud attempt suspicions'
        ],

        [
            'codes'     => ['43'],
            'sys-msg'   => 'Card is reported as stolen',
            'message'   => 'Your card is reported as stolen. Do not try to charge this card again due to fraud attempt suspicions'
        ],

        [
            'codes'     => ['51'],
            'sys-msg'   => 'Insufficient funds for transaction',
            'message'   => 'Operation failed due to insufficient funds - check your balance and try again'
        ],

        [
            'codes'     => ['54'],
            'sys-msg'   => 'Card is outdated',
            'message'   => 'Your card is outdated - use another card or contact your bank'
        ],

        [
            'codes'     => ['59'],
            'sys-msg'   => 'Fraud suspicion',
            'message'   => 'Operation failed - please contact your bank immediately'
        ],

        [
            'codes'     => ['62'],
            'sys-msg'   => 'Card is limited, card is excluded because of the issuer country (embargo)',
            'message'   => 'Your card is declined dur to its origin - please contact your bank'
        ],

        [
            'codes'     => ['65'],
            'sys-msg'   => 'Card usage limit reached',
            'message'   => 'Your card is declined due to daily usage limit - check your limits and try again'
        ],

        [
            'codes'     => ['75'],
            'sys-msg'   => 'Limit of security codes (CVV/CVC/PIN) checks exceeded',
            'message'   => 'Limit of security codes (CVV/CVC/PIN) checks exceeded'
        ],

        [
            'codes'     => ['78'],
            'sys-msg'   => 'Brand new card - not activated',
            'message'   => 'Your card is not activated - activate it and try again'
        ],

        [
            'codes'     => ['82', 'N7'],
            'sys-msg'   => 'Wrong CVV code',
            'message'   => 'Wrong CVV code - check your code and try again'
        ],

        [
            'codes'     => ['91', '92', '94', '98'],
            'sys-msg'   => 'Temporary problems with bank',
            'message'   => 'Operation failed - please try again'
        ],
    ];

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $systemMessage;

    /**
     * @var string
     */
    private $message;

    /**
     * @param string $code
     *
     * @throws UndefinedPaymentErrorException
     */
    public function __construct(string $code)
    {
        foreach (self::$mapping as $error) {
            if (in_array($code, $error['codes'])) {
                $this->code = $code;
                $this->systemMessage = $error['sys-msg'];
                $this->message = $error['message'];

                return;
            }
        }

        throw new UndefinedPaymentErrorException(
            sprintf('Undefined error code in Espago Api Error Mapper: %s', $code)
        );
    }

    /**
     * @param string $code
     *
     * @return PaymentOperationError
     */
    public static function create(string $code): PaymentOperationError
    {
        return new self($code);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getSystemMessage(): string
    {
        return $this->systemMessage;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}