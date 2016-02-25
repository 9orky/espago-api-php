<?php

namespace Gorky\Espago\Builder;

use Gorky\Espago\Api\Call\HttpCall;
use Gorky\Espago\Value\ApiCredentials;
use Gorky\Espago\Value\HttpAuth;

class HttpCallBuilder
{
    /**
     * @var ApiCredentials
     */
    private $apiCredentials;

    /**
     * @param ApiCredentials $apiCredentials
     */
    public function __construct(ApiCredentials $apiCredentials)
    {
        $this->apiCredentials = $apiCredentials;
    }

    /**
     * @param string $url
     * @param HttpAuth $httpAuth
     * @return HttpCall
     */
    public function buildGetCall(HttpAuth $httpAuth, $url)
    {
        return new HttpCall(
            $url,
            HttpCall::METHOD_GET,
            $httpAuth->getUsername(),
            $httpAuth->getPassword()
        );
    }

    /**
     * @param HttpAuth $httpAuth
     * @param string $url
     * @param array $payload
     * @return HttpCall
     */
    public function buildPostCall(HttpAuth $httpAuth, $url, array $payload)
    {
        $call = new HttpCall(
            $url,
            HttpCall::METHOD_POST,
            $httpAuth->getUsername(),
            $httpAuth->getPassword()
        );

        $call->setFormData($payload);

        return $call;
    }

    /**
     * @param string $url
     * @param array $payload
     * @return HttpCall
     */
    public function buildPutCall($url, array $payload)
    {
        $call = new HttpCall(
            $url,
            HttpCall::METHOD_PUT,
            $this->apiCredentials->getAppId(),
            $this->apiCredentials->getPassword()
        );

        $call->setFormData($payload);

        return $call;
    }

    /**
     * @param string $url
     * @return HttpCall
     */
    public function buildDeleteCall($url)
    {
        return new HttpCall(
            $url,
            HttpCall::METHOD_DELETE,
            $this->apiCredentials->getAppId(),
            $this->apiCredentials->getPassword()
        );
    }
}