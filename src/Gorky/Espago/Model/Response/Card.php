<?php

namespace Gorky\Espago\Model\Response;

class Card
{
    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $year;

    /**
     * @var string
     */
    private $month;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @param string $company
     * @param string $number
     * @param string $firstName
     * @param string $lastName
     * @param string $year
     * @param string $month
     * @param string $createdAt
     */
    public function __construct($company, $number, $firstName, $lastName, $year, $month, $createdAt)
    {
        $this->company   = $company;
        $this->number    = $number;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->year      = $year;
        $this->month     = $month;
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getObfuscatedNumber()
    {
        return str_pad($this->number, 16, '*', STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFirstAndLastName()
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return (new \DateTime())->setTimestamp($this->createdAt);
    }
}