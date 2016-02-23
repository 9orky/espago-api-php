<?php

namespace Gorky\Espago\Model;

class UnauthorizedCard
{
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
    private $code;

    /**
     * @param $number
     * @param $firstName
     * @param $lastName
     * @param $month
     * @param $year
     * @param $code
     */
    public function __construct($number, $firstName, $lastName, $month, $year, $code)
    {
        $this->number    = $number;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
        $this->month     = $month;
        $this->year      = $year;
        $this->code      = $code;
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
    public function getMonth()
    {
        return $this->month;
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
    public function getCode()
    {
        return $this->code;
    }
}