<?php

namespace Gorky\Espago\Factory;

use Gorky\Espago\Http\HttpCall;
use Gorky\Espago\Value\ApiCredentials;

class HttpCallFactory
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
     *
     * @return HttpCall
     */
    public function buildGetCall($url)
    {
        return new HttpCall(
            $url,
            HttpCall::METHOD_GET,
            $this->apiCredentials->getAppId(),
            $this->apiCredentials->getPassword()
        );
    }

    /**
     * @param string $url
     * @param array $payload
     *
     * @return HttpCall
     */
    public function buildPostCall($url, array $payload = [])
    {
        $call = new HttpCall(
            $url,
            HttpCall::METHOD_POST,
            $this->apiCredentials->getAppId(),
            $this->apiCredentials->getPassword()
        );

        if ($payload) {
            $call->setFormData($payload);
        }
        
        return $call;
    }

    /**
     * @param string $url
     * @param array $payload
     *
     * @return HttpCall
     */
    public function buildPostCallAuthorizedWithPublicKey($url, array $payload = [])
    {
        $call = new HttpCall(
            $url,
            HttpCall::METHOD_POST,
            $this->apiCredentials->getPublicKey(),
            '' // password is empty in this case
        );

        if ($payload) {
            $call->setFormData($payload);
        }

        return $call;
    }

    /**
     * @param $url
     * @param array $payload
     *
     * @return HttpCall
     */
    public function buildPutCall($url, array $payload = null)
    {
        $call = new HttpCall(
            $url,
            HttpCall::METHOD_PUT,
            $this->apiCredentials->getAppId(),
            $this->apiCredentials->getPassword()
        );

        if (null !== $payload) {
            $call->setFormData($payload);
        }

        return $call;
    }

    /**
     * @param string $url
     *
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