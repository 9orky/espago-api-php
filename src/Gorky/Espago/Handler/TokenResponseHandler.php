<?php

namespace Gorky\Espago\Handler;

use Gorky\Espago\Value\Token;

class TokenResponseHandler extends AbstractResponseHandler
{
    /**
     * @param array $apiResponse
     *
     * @return Token
     */
    public function handle(array $apiResponse)
    {
        return new Token($apiResponse['id']);
    }
}