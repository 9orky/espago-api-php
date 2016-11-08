<?php

declare(strict_types = 1);

namespace Gorky\Espago\Handler;

use Gorky\Espago\Http\HttpResponse;
use Gorky\Espago\Model\Response\Token;

class TokenResponseHandler extends AbstractResponseHandler
{
    /**
     * @param HttpResponse $httpResponse
     *
     * @return Token
     */
    public function handle(HttpResponse $httpResponse)
    {
        return new Token($httpResponse->getData()['id']);
    }
}