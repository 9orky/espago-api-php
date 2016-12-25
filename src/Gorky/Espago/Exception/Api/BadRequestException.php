<?php

declare(strict_types = 1);

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
     *
     * @return void
     */
    public function addBadRequestError(BadRequestError $badRequestError): void
    {
        $this->badRequestErrors[] = $badRequestError;
    }

    /**
     * @return BadRequestError[]
     *
     * @return array
     */
    public function getBadRequestErrors(): array
    {
        return $this->badRequestErrors;
    }
}