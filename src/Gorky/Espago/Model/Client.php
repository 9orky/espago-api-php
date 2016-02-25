<?php

namespace Gorky\Espago\Model;


class Client
{
    
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $email;
    /**
     * @var
     */
    private $description;
    /**
     * @var
     */
    private $createdAt;

    /**
     * Client constructor.
     * @param $id
     * @param $email
     * @param $description
     * @param $createdAt
     */
    public function __construct($id, $email, $description, $createdAt)
    {
        $this->id          = $id;
        $this->email       = $email;
        $this->description = $description;
        $this->createdAt   = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}