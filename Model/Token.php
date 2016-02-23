<?php

namespace Gorky\Espago\Model;

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