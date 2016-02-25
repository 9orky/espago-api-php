<?php

namespace Gorky\Espago\Error;

class PaymentOperationError
{
    /**
     * @var
     */
    private $code;

    /**
     * @var
     */
    private $systemMessage;

    /**
     * @var
     */
    private $message;

    /**
     * @param $code
     * @param $systemMessage
     * @param $message
     */
    function __construct($code, $systemMessage, $message)
    {
        $this->code = $code;
        $this->systemMessage = $systemMessage;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getSystemMessage()
    {
        return $this->systemMessage;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
}