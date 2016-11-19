<?php

namespace Gorky\Espago\Model\Response;

class DccDecision
{
    /**
     * @var string
     */
    private $cardHolderCurrencyName;

    /**
     * @var string
     */
    private $cardHolderAmount;

    /**
     * @var string
     */
    private $conversionRate;

    /**
     * @param string $cardHolderCurrencyName
     * @param string $cardHolderAmount
     * @param string $conversionRate
     */
    public function __construct(string $cardHolderCurrencyName, string $cardHolderAmount, string $conversionRate)
    {
        $this->cardHolderCurrencyName = $cardHolderCurrencyName;
        $this->cardHolderAmount = $cardHolderAmount;
        $this->conversionRate = $conversionRate;
    }

    /**
     * @return string
     */
    public function getCardHolderCurrencyName(): string
    {
        return $this->cardHolderCurrencyName;
    }

    /**
     * @return float
     */
    public function getCardHolderAmount(): float
    {
        return (float) $this->cardHolderAmount;
    }

    /**
     * @return float
     */
    public function getConversionRate(): float
    {
        return (float) $this->conversionRate;
    }
}