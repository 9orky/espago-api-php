<?php

namespace Gorky\Espago\Api\Call;

use Gorky\Espago\Exception\ApiHttpCallHeaderExistsException;

class HttpCall
{
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var
     */
    private $httpAuthUsername;

    /**
     * @var
     */
    private $httpAuthPassword;

    /**
     * @var
     */
    private $url;

    /**
     * @var
     */
    private $method;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var
     */
    private $formData;

    /**
     * HttpCall constructor.
     * @param $url
     * @param $method
     * @param $httpAuthUsername
     * @param $httpAuthPassword
     */
    public function __construct($url, $method, $httpAuthUsername, $httpAuthPassword)
    {
        $this->url              = $url;
        $this->method           = $method;
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
        $this->headers[$headerName] = $headerValue;

        return $this;
    }

    /**
     * @param $headerName
     * @param $headerValue
     * @throws ApiHttpCallHeaderExistsException
     */
    public function appendHeader($headerName, $headerValue)
    {
        if (isset($this->headers[$headerName])) {
            throw new ApiHttpCallHeaderExistsException(
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
}