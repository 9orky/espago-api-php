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
     * @var Card
     */
    private $card;

    /**
     * @param string $id
     * @param string $email
     * @param string $description
     * @param int $createdAt
     * @param Card $card
     */
    public function __construct($id, $email, $description, $createdAt, Card $card)
    {
        $this->id = $id;
        $this->email = $email;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->card = $card;
    }


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }
}