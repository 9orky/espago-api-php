<?php
namespace Gorky\Espago\Response\Client;

class DeleteClient
{
    /**
     * @var bool
     */
    private $successful;

    /**
     * @param bool $successful
     */
    public function __construct($successful)
    {
        $this->successful = $successful;
    }

    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->successful;
    }
}