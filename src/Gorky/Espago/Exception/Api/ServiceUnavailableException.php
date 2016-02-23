<?php

namespace Gorky\Espago\Exception\Api;

use Gorky\Espago\Exception\EspagoException;

class ServiceUnavailableException extends EspagoException
{
    const USER_VISIBLE_ERROR_MESSAGE = 'We are having problems with processing your payment. Please try again.';
}