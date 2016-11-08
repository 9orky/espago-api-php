<?php

declare(strict_types = 1);

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
    public function __construct(
        string $company,
        string $number,
        string $firstName,
        string $lastName,
        string $year,
        string $month,
        string $createdAt
    ) {
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
    public function getCompany(): string
    {
        return $this->company;
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
    public function getObfuscatedNumber(): string
    {
        return str_pad($this->number, 16, '*', STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public function getFirstAndLastName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return (new \DateTime())->setTimestamp($this->createdAt);
    }
}