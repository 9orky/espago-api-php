<?php

namespace Gorky\Espago;

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
    private $formData = [];

    /**
     * @param string $url
     * @param string $method
     * @param string $httpAuthUsername
     * @param string $httpAuthPassword
     *
     * @throws HttpCallUnsupportedMethodException
     */
    public function __construct($url, $method, $httpAuthUsername, $httpAuthPassword)
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
    public static function getSupportedMethods()
    {
        return [
            self::METHOD_GET,
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_DELETE
        ];
    }

    /**
     * @param $headerName
     * @param $headerValue
     * @return $this
     */
    public function setHeader($headerName, $headerValue)
    {
        if ('Accept' === $headerName) {
            throw new \InvalidArgumentException('Accept header determines an API version so this is read-only');
        }

        $this->headers[$headerName] = $headerValue;

        return $this;
    }

    /**
     * @param $headerName
     * @param $headerValue
     * @throws HttpCallHeaderExistsException
     */
    public function appendHeader($headerName, $headerValue)
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
     * @return $this
     */
    public function setFormData(array $formData)
    {
        $this->formData = $formData;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHttpAuthUsername()
    {
        return $this->httpAuthUsername;
    }

    /**
     * @return mixed
     */
    public function getHttpAuthPassword()
    {
        return $this->httpAuthPassword;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return var_export($this, true);
    }
}