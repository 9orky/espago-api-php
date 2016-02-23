<?php

namespace Gorky\Espago\Exception\Api;

use Gorky\Espago\Error\BadRequestError;
use Gorky\Espago\Exception\EspagoException;

class BadRequestException extends EspagoException
{
    /**
     * @var BadRequestError[]
     */
    private $badRequestErrors = [];

    /**
     * @param BadRequestError $badRequestError
     */
    public function addBadRequestError(BadRequestError $badRequestError)
    {
        $this->badRequestErrors[] = $badRequestError;
    }

    /**
     * @return BadRequestError[]
     */
    public function getBadRequestErrors()
    {
        return $this->badRequestErrors;
    }
}