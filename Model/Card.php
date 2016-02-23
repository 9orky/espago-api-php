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
     * Card constructor.
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
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}