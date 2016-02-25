<?php

namespace Gorky\Espago\Api\Method;

use Gorky\Espago\Model\CardModel;
use Gorky\Espago\Model\TokenModel;

class CreateCardTokenResponse
{

    /**
     * @var TokenModel
     */
    private $token;

    /**
     * @var CardModel
     */
    private $card;

    /**
     * @param TokenModel $token
     * @param CardModel $card
     */
    public function __construct(TokenModel $token, CardModel $card)
    {
        $this->token = $token;
        $this->card = $card;
    }

    /**
     * @return TokenModel
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return CardModel
     */
    public function getCard()
    {
        return $this->card;
    }


}