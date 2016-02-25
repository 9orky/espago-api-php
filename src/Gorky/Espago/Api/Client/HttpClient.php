<?php

namespace Gorky\Espago\Api\Client;

use Gorky\Espago\Api\Call\HttpCall;
use Gorky\Espago\Exception\ApiHttpCallUnsupportedMethodException;
use Gorky\Espago\Exception\EspagoApiBadRequestException;
use Gorky\Espago\Exception\EspagoApiUnavailableException;
use Gorky\Espago\Exception\NetworkConnectionException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;

class HttpClient
{
    /**
     * @var
     */
    private $apiUrl;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $apiUrl
     */
    public function __construct($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $this->client = new Client();
    }

    /**
     * @param HttpCall $httpCall
     * @return array
     * @throws ApiHttpCallUnsupportedMethodException
     * @throws EspagoApiBadRequestException
     * @throws EspagoApiUnavailableException
     * @throws NetworkConnectionException
     */
    public function makeCall(HttpCall $httpCall)
    {
        if (!in_array($httpCall->getMethod(), HttpCall::getSupportedMethods())) {
            throw new ApiHttpCallUnsupportedMethodException("{$httpCall->getMethod()} is not supported HTTP method");
        }

        try {
            switch ($httpCall->getMethod()) {
                case HttpCall::METHOD_GET:
                    return $this->get($httpCall)->json();

                case HttpCall::METHOD_POST:
                    return $this->post($httpCall)->json();

                case HttpCall::METHOD_PUT:
                    return $this->put($httpCall)->json();

                case HttpCall::METHOD_DELETE:
                    return $this->delete($httpCall)->json();
            }
        } catch (ConnectException $e) {
            throw new NetworkConnectionException('Espago API is unreachable. Debug network and check if API is online');
        } catch (ClientException $e) {
            throw new EspagoApiBadRequestException("Bad request to Espago API. {$e->getMessage()}");
        } catch (ServerException $e) {
            throw new EspagoApiUnavailableException('Received a server error when connecting to Espago API');
        }
    }

    /**
     * @param $headerName
     * @param array $values
     * @return array
     */
    public function createHeader($headerName, array $values)
    {
        return [$headerName => $values];
    }

    /**
     * @param HttpCall $httpCall
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(HttpCall $httpCall)
    {
        $url = sprintf('%s%s', $this->apiUrl, $httpCall->getUrl());

        return $this->client->get($url, [
            'auth'    => [$httpCall->getHttpAuthUsername(), $httpCall->getHttpAuthPassword()],
            'headers' => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param HttpCall $httpCall
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function post(HttpCall $httpCall)
    {
        $url = sprintf('%s%s', $this->apiUrl, $httpCall->getUrl());

        return $this->client->post($url, [
            'auth'        => [$httpCall->getHttpAuthUsername(), $httpCall->getHttpAuthPassword()],
            'form_params' => $httpCall->getFormData(),
            'headers'     => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param HttpCall $httpCall
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function put(HttpCall $httpCall)
    {
        $url = sprintf('%s%s', $this->apiUrl, $httpCall->getUrl());

        return $this->client->put($url, [
            'auth'        => [$httpCall->getHttpAuthUsername(), $httpCall->getHttpAuthPassword()],
            'form_params' => $httpCall->getFormData(),
            'headers'     => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param HttpCall $httpCall
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(HttpCall $httpCall)
    {
        $url = sprintf('%s%s', $this->apiUrl, $httpCall->getUrl());

        return $this->client->delete($url, [
            'auth'    => [$httpCall->getHttpAuthUsername(), $httpCall->getHttpAuthPassword()],
            'headers' => $httpCall->getHeaders()
        ]);
    }
}