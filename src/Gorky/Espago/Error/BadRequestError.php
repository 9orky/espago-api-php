<?php

namespace Gorky\Espago\Error;

class BadRequestError
{

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $param;

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $param
     * @param string $message
     * @param string $code
     * @param string $type
     *
     * @return BadRequestError
     */
    public static function create($param, $message, $code = null, $type = null)
    {
        return new self($param, $message, $code, $type);
    }

    /**
     * @param string $param
     * @param string $message
     * @param string $code
     * @param string $type
     */
    public function __construct($param, $message, $code = null, $type = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->param = $param;
        $this->type = $type;
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('[%s][%s] %s', $this->type, $this->param, $this->message);
    }
}