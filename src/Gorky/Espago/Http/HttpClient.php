<?php

declare(strict_types = 1);

namespace Gorky\Espago\Http;

use Gorky\Espago\Error\BadRequestError;
use Gorky\Espago\Exception\Api\BadRequestException;
use Gorky\Espago\Exception\Api\MalformedResponseException;
use Gorky\Espago\Exception\Api\ResourceNotFoundException;
use Gorky\Espago\Exception\Api\ServiceUnavailableException;
use Gorky\Espago\Exception\Call\HttpCallUnsupportedMethodException;
use Gorky\Espago\Exception\Transport\NetworkConnectionException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpClient
{
    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $apiUrl
     */
    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $this->client = new Client();
    }

    /**
     * @param HttpCall $httpCall
     * @param callable $clientExceptionCallback Stupid hack because of API errors inconsistent format
     *
     * @return HttpResponse
     *
     * @throws BadRequestException
     * @throws NetworkConnectionException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     * @throws HttpCallUnsupportedMethodException
     */
    public function makeCall(HttpCall $httpCall, callable $clientExceptionCallback = null): HttpResponse
    {
        try {
            switch ($httpCall->getMethod()) {
                case HttpCall::METHOD_GET:
                    $response = $this->get($httpCall);
                    break;
                case HttpCall::METHOD_POST:
                    $response = $this->post($httpCall);
                    break;
                case HttpCall::METHOD_PUT:
                    $response = $this->put($httpCall);
                    break;
                case HttpCall::METHOD_DELETE:
                    $response = $this->delete($httpCall);
                    break;
                default:
                    throw new HttpCallUnsupportedMethodException(
                        sprintf('Unknown HTTP method: %s', $httpCall->getMethod())
                    );
            }

            return new HttpResponse(
                $response->getStatusCode(),
                $this->responseToArray($response->getBody())
            );
        } catch (ConnectException $e) {
            throw new NetworkConnectionException('Espago API is unreachable. Debug network and check if API is online');
        } catch (ServerException $e) {
            throw new ServiceUnavailableException('Received a server error when connecting to Espago API');
        } catch (ClientException $e) {
            if ($clientExceptionCallback) {
                return $clientExceptionCallback($e);
            }

            if ($e->getResponse()->getStatusCode() === 404) {
                throw new ResourceNotFoundException('Resource was not found');
            }

            $response = $this->responseToArray($e->getResponse()->getBody());
            $badRequestException = new BadRequestException();

            if (isset($response['errors'])) {
                foreach ($response['errors'] as $error) {
                    $badRequestException->addBadRequestError(
                        BadRequestError::create($error['param'], $error['message'], $error['code'], $error['type'])
                    );
                }
            }

            throw $badRequestException;
        }
    }

    /**
     * @param HttpCall $httpCall
     *
     * @return ResponseInterface
     */
    private function get(HttpCall $httpCall): ResponseInterface
    {
        return $this->client->get($this->buildUrl($this->apiUrl, $httpCall->getUrl()), [
            'auth'    => [$httpCall->getHttpAuthUsername(), $httpCall->getHttpAuthPassword()],
            'headers' => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param HttpCall $httpCall
     *
     * @return ResponseInterface
     */
    private function post(HttpCall $httpCall): ResponseInterface
    {
        return $this->client->post($this->buildUrl($this->apiUrl, $httpCall->getUrl()), [
            'auth'        => [$httpCall->getHttpAuthUsername(), $httpCall->getHttpAuthPassword()],
            'form_params' => $httpCall->getFormData(),
            'headers'     => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param HttpCall $httpCall
     *
     * @return ResponseInterface
     */
    private function put(HttpCall $httpCall): ResponseInterface
    {
        return $this->client->put($this->buildUrl($this->apiUrl, $httpCall->getUrl()), [
            'auth'        => [$httpCall->getHttpAuthUsername(), $httpCall->getHttpAuthPassword()],
            'form_params' => $httpCall->getFormData(),
            'headers'     => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param HttpCall $httpCall
     *
     * @return ResponseInterface
     */
    private function delete(HttpCall $httpCall): ResponseInterface
    {
        return $this->client->delete($this->buildUrl($this->apiUrl, $httpCall->getUrl()), [
            'auth'    => [$httpCall->getHttpAuthUsername(), $httpCall->getHttpAuthPassword()],
            'headers' => $httpCall->getHeaders()
        ]);
    }

    /**
     * @param string $apiUrl
     * @param string $endpointUrl
     *
     * @return string
     */
    private function buildUrl(string $apiUrl, string $endpointUrl): string
    {
        return sprintf('%s%s', $apiUrl, $endpointUrl);
    }

    /**
     * @param StreamInterface $response
     *
     * @return array
     *
     * @throws MalformedResponseException
     */
    private function responseToArray(StreamInterface $response): array
    {
        $arrayResponse = json_decode($response->getContents(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new MalformedResponseException(json_last_error_msg());
        }
        
        return $arrayResponse;
    }
}