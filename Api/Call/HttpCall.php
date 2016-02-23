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
    private $appId;

    /**
     * @var
     */
    private $password;

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
     * @param $url
     * @param $method
     * @param $appId
     * @param $password
     */
    public function __construct($url, $method, $appId, $password)
    {
        $this->url      = $url;
        $this->method   = $method;
        $this->appId    = $appId;
        $this->password = $password;

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
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
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