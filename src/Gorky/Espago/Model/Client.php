<?php

namespace Gorky\Espago\Model;

class Client
{
    /**
     * @var string
     */
    private $id;
    
    /**
     * @var string
     */
    private $email;
    
    /**
     * @var string
     */
    private $description;
    
    /**
     * @var int
     */
    private $createdAt;

    /**
     * @param string $id
     * @param string $email
     * @param string $description
     * @param int $createdAt
     */
    public function __construct($id, $email, $description, $createdAt)
    {
        $this->id = $id;
        $this->email = $email;
        $this->description = $description;
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }
}