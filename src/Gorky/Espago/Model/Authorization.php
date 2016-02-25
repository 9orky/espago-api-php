<?php

namespace Gorky\Espago\Model;

class Authorization
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
    private $deleted;

    /**
     * @var
     */
    private $createdAt;

    /**
     * Authorization constructor.
     * @param $id
     * @param $email
     * @param $description
     * @param $deleted
     * @param $createdAt
     */
    public function __construct($id, $email, $description, $deleted, $createdAt)
    {
        $this->id = $id;
        $this->email = $email;
        $this->description = $description;
        $this->deleted = $deleted;
        $this->createdAt = $createdAt;
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
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}