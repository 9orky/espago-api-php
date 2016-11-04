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
     * @param string $number
     * @param string $firstName
     * @param string $lastName
     * @param string $month
     * @param string $year
     * @param string $code
     */
    public function __construct(
        string $number,
        string $firstName,
        string $lastName,
        string $month,
        string $year,
        string $code
    ) {
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
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getYear(): string
    {
        return $this->year;
    }

    /**
     * @return string
     */
    public function getMonth(): string
    {
        return $this->month;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}