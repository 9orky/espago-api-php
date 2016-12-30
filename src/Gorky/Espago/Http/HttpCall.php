<?php

declare(strict_types = 1);

namespace Gorky\Espago\Http;

use Gorky\Espago\Exception\Call\HttpCallHeaderExistsException;
use Gorky\Espago\Exception\Call\HttpCallUnsupportedMethodException;

class HttpCall
{
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string
     */
    private $httpAuthUsername;

    /**
     * @var string
     */
    private $httpAuthPassword;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $formData;

    /**
     * @param string $url
     * @param string $method
     * @param string $httpAuthUsername
     * @param string $httpAuthPassword
     *
     * @throws HttpCallUnsupportedMethodException
     */
    public function __construct(string $url, string $method, string $httpAuthUsername, string $httpAuthPassword)
    {
        if (!in_array($method, HttpCall::getSupportedMethods())) {
            throw new HttpCallUnsupportedMethodException(
                sprintf('%s is not supported HTTP method', $method)
            );
        }

        $this->url = $url;
        $this->method = $method;
        $this->httpAuthUsername = $httpAuthUsername;
        $this->httpAuthPassword = $httpAuthPassword;

        $this->headers = ['Accept' => 'application/vnd.espago.v3+json'];
    }

    /**
     * @return array
     */
    public static function getSupportedMethods(): array
    {
        return [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_DELETE
        ];
    }

    /**
     * @param string $headerName
     * @param string $headerValue
     *
     * @return self
     */
    public function setHeader(string $headerName, string $headerValue): HttpCall
    {
        if ('Accept' === $headerName) {
            throw new \InvalidArgumentException('Accept header determines an API version so this is read-only');
        }

        $this->headers[$headerName] = $headerValue;

        return $this;
    }

    /**
     * @param string $headerName
     * @param string $headerValue
     *
     * @return void
     *
     * @throws HttpCallHeaderExistsException
     */
    public function appendHeader(string $headerName, string $headerValue)
    {
        if (isset($this->headers[$headerName])) {
            throw new HttpCallHeaderExistsException(
                "Header {$headerName} is already defined with value: {$headerValue} and cannot be overwritten"
            );
        }

        $this->setHeader($headerName, $headerValue);
    }

    /**
     * @param array $formData
     *
     * @return self
     */
    public function setFormData(array $formData = []): HttpCall
    {
        $this->formData = $formData;

        return $this;
    }

    /**
     * @return string
     */
    public function getHttpAuthUsername(): string
    {
        return $this->httpAuthUsername;
    }

    /**
     * @return string
     */
    public function getHttpAuthPassword(): string
    {
        return $this->httpAuthPassword;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getFormData(): array
    {
        return $this->formData;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return var_export($this, true);
    }
}