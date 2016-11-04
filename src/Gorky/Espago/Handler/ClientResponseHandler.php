<?php

declare(strict_types = 1);

namespace Gorky\Espago\Handler;

use Gorky\Espago\Http\HttpResponse;
use Gorky\Espago\Model\Response\Card;
use Gorky\Espago\Model\Response\Client;

class ClientResponseHandler extends AbstractResponseHandler
{
    /**
     * @param HttpResponse $httpResponse
     *
     * @return Client
     */
    public function handle(HttpResponse $httpResponse): Client
    {
        $apiResponse = $httpResponse->getData();

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