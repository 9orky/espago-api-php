<?php

namespace Gorky\Espago\Api\Response;

use Gorky\Espago\Model\Authorization;
use Gorky\Espago\Model\Card;

class CreateAuthorizationResponse
{

    /**
     * @var Authorization
     */
    private $authorization;

    /**
     * @var Card
     */
    private $card;

    /**
     * @param Authorization $authorization
     * @param Card $card
     */
    public function __construct(Authorization $authorization, Card $card)
    {
        $this->authorization = $authorization;
        $this->card = $card;
    }

    /**
     * @return Authorization
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }
}