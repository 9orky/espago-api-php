<?php

declare(strict_types = 1);

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
     * @param string|null $code
     * @param string|null $type
     *
     * @return BadRequestError
     */
    public static function create(string $param, string $message, string $code = null, string $type = null): BadRequestError
    {
        return new self($param, $message, $code, $type);
    }

    /**
     * @param string $param
     * @param string $message
     * @param string $code
     * @param string $type
     */
    public function __construct(string $param, string $message, string $code = null, string $type = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->param = $param;
        $this->type = $type;
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
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getParam(): string
    {
        return $this->param;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('[%s][%s] %s', $this->type, $this->param, $this->message);
    }
}