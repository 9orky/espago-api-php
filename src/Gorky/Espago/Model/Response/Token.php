<?php

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
    function __construct($tokenValue)
    {
        $this->tokenValue = $tokenValue;
    }

    /**
     * @return string
     */
    public function getTokenValue()
    {
        return $this->tokenValue;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->tokenValue;
    }
}