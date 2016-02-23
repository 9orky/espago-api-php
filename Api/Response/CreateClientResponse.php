<?php

namespace Gorky\Espago\Api\Response;

use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Client;

class CreateClientResponse
{

    /**
     * @var
     */
    private $client;

    /**
     * @var
     */
    private $card;

    /**
     * @param Client $client
     * @param Card $card
     */
    public function __construct(Client $client, Card $card)
    {
        $this->client = $client;
        $this->card = $card;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return mixed
     */
    public function getCard()
    {
        return $this->card;
    }
}