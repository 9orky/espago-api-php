<?php

namespace Gorky\Espago\Api\Response;


class DeleteClientResponse
{

    /**
     * @var bool
     */
    private $deleted;

    /**
     * @param $deleted
     */
    public function __construct($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return mixed
     */
    public function deletedSuccessfully()
    {
        return $this->deleted;
    }
}