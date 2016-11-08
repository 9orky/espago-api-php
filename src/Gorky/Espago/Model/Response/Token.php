<?php

declare(strict_types = 1);

namespace Gorky\Espago\Model\Response;

class Token
{
    /**
     * @var string
     */
    private $tokenValue;

    /**
     * @param string $tokenValue
     */
    function __construct(string $tokenValue)
    {
        $this->tokenValue = $tokenValue;
    }

    /**
     * @return string
     */
    public function getTokenValue(): string
    {
        return $this->tokenValue;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->tokenValue;
    }
}