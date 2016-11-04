<?php

namespace Gorky\Espago\Handler;

use Gorky\Espago\Http\HttpResponse;

abstract class AbstractResponseHandler
{
    /**
     * @param HttpResponse $httpResponse
     *
     * @return mixed
     */
    abstract public function handle(HttpResponse $httpResponse);
}