<?php

namespace Gorky\Espago\Api\Response;

use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Charge;

class CreateChargeResponse
{

    /**
     * @var Charge
     */
    private $charge;

    /**
     * @var Card
     */
    private $card;

    /**
     * @param Charge $charge
     * @param Card $card
     */
    public function __construct(Charge $charge, Card $card)
    {
        $this->charge = $charge;
        $this->card = $card;
    }

    /**
     * @return Charge
     */
    public function getCharge()
    {
        return $this->charge;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }
}