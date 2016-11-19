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
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @param string $company
     * @param string $number
     * @param string $firstName
     * @param string $lastName
     * @param int $year
     * @param int $month
     * @param int $createdAt
     */
    public function __construct(
        string $company,
        string $number,
        string $firstName,
        string $lastName,
        int $year,
        int $month,
        int $createdAt
    ) {
        $this->company = $company;
        $this->number = $number;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->year = $year;
        $this->month = $month;
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
        return (string) $this->year;
    }

    /**
     * @return string
     */
    public function getMonth(): string
    {
        return (string) $this->month;
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