<?php

declare(strict_types = 1);

namespace Gorky\Espago\Http;

class HttpResponse
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $data;

    /**
     * @param int $statusCode
     * @param array $data
     */
    public function __construct(int $statusCode, array $data)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}