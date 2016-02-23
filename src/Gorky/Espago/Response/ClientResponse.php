<?php

namespace Gorky\Espago\Response;

use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Client;

class ClientResponse
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Card
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
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }
}