<?php

declare(strict_types = 1);

namespace Gorky\Espago\Model\Response;

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
     * @var \DateTime
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
    public function __construct(string $id, string $email, string $description, int $createdAt, Card $card)
    {
        $this->id = $id;
        $this->email = $email;
        $this->description = $description;
        $this->createdAt = (new \DateTime())->setTimestamp($createdAt);
        $this->card = $card;
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }
}