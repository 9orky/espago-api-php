<?php

namespace Gorky\Espago\Value;

class Token
{

    /**
     * @var
     */
    private $tokenValue;

    /**
     * @param $tokenValue
     */
    function __construct($tokenValue)
    {
        $this->tokenValue = $tokenValue;
    }

    /**
     * @return mixed
     */
    public function getTokenValue()
    {
        return $this->tokenValue;
    }
}