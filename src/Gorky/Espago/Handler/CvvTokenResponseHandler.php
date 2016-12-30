<?php

namespace Gorky\Espago\Handler;

use Gorky\Espago\Http\HttpResponse;
use Gorky\Espago\Model\Response\CvvToken;

class CvvTokenResponseHandler extends AbstractResponseHandler
{
    /**
     * @param HttpResponse $httpResponse
     *
     * @return CvvToken
     */
    public function handle(HttpResponse $httpResponse)
    {
        $data = $httpResponse->getData();

        return new CvvToken(
            $data['id'],
            $data['valid_to'],
            $data['created_at'],
            $data['used']
        );
    }
}