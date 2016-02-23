<?php

namespace Gorky\Espago\Model;

class Card
{
    /**
     * @var
     */
    private $company;

    /**
     * @var
     */
    private $number;

    /**
     * @var
     */
    private $firstName;

    /**
     * @var
     */
    private $lastName;

    /**
     * @var
     */
    private $year;

    /**
     * @var
     */
    private $month;

    /**
     * @var
     */
    private $createdAt;

    /**
     * @param $company
     * @param $number
     * @param $firstName
     * @param $lastName
     * @param $year
     * @param $month
     * @param $createdAt
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
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
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