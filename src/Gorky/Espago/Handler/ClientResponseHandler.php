<?php

namespace Gorky\Espago\Handler;

use Gorky\Espago\Model\Card;
use Gorky\Espago\Model\Client;

class ClientResponseHandler extends AbstractResponseHandler
{
    /**
     * @param array $apiResponse
     *
     * @return Client
     */
    public function handle(array $apiResponse)
    {
        return new Client(
            $apiResponse['id'],
            $apiResponse['email'],
            $apiResponse['description'],
            $apiResponse['created_at'],
            new Card(
                $apiResponse['card']['company'],
                $apiResponse['card']['last4'],
                $apiResponse['card']['first_name'],
                $apiResponse['card']['last_name'],
                $apiResponse['card']['year'],
                $apiResponse['card']['month'],
                $apiResponse['card']['created_at']
            )
        );
    }
}