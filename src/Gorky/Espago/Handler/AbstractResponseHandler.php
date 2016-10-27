<?php

namespace Gorky\Espago\Handler;

abstract class AbstractResponseHandler
{
    /**
     * @param array $apiResponse
     *
     * @return mixed
     */
    abstract public function handle(array $apiResponse);
}