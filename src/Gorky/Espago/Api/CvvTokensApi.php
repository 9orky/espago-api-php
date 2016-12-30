<?php

declare(strict_types = 1);

namespace Gorky\Espago\Api;

use Gorky\Espago\Model\Response\CvvToken;

class CvvTokensApi extends AbstractApi
{
    /**
     * @return CvvToken
     */
    public function createCvvToken(): CvvToken
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildPostCallAuthorizedWithPublicKey(
                '/api/cvv'
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }

    /**
     * @param string $cvvId
     *
     * @return CvvToken
     */
    public function getCvvToken(string $cvvId): CvvToken
    {
        $apiResponse = $this->httpClient->makeCall(
            $this->httpCallFactory->buildGetCall(
                sprintf('/api/cvv/%s', $cvvId)
            )
        );

        return $this->responseHandler->handle($apiResponse);
    }
}