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
     * @var Client
     */
    private $client;

    /**
     * @param $espagoApiUrl
     */
    public function __construct($espagoApiUrl)
    {
        $this->client = new Client([
            'base_url' => $espagoApiUrl
        ]);
    }

    /**
     * @param HttpCall $httpCall
     * @return \GuzzleHttp\Message\ResponseInterface
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
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function get(HttpCall $httpCall)
    {
        return $this->client->get($httpCall->getUrl(), [
            'auth'    => [$httpCall->getAppId(), $httpCall->getPassword()],
            'headers' => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param HttpCall $httpCall
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function post(HttpCall $httpCall)
    {
        return $this->client->post($httpCall->getUrl(), [
            'auth'    => [$httpCall->getAppId(), $httpCall->getPassword()],
            'body'    => $httpCall->getFormData(),
            'headers' => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param HttpCall $httpCall
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function put(HttpCall $httpCall)
    {
        return $this->client->put($httpCall->getUrl(), [
            'auth'    => [$httpCall->getAppId(), $httpCall->getPassword()],
            'body'    => $httpCall->getFormData(),
            'headers' => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param HttpCall $httpCall
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function delete(HttpCall $httpCall)
    {
        return $this->client->delete($httpCall->getUrl(), [
            'auth'    => [$httpCall->getAppId(), $httpCall->getPassword()],
            'headers' => $httpCall->getHeaders()
        ]);
    }
}