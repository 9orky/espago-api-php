<?php

namespace Gorky\Espago\Api\Response;

use Gorky\Espago\Model\Token;

class GetTokenResponse
{

    /**
     * @var Token
     */
    private $token;

    /**
     * @param Token $token
     */
    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }
}