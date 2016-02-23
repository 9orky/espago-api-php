<?php

namespace Gorky\Espago\Model;

class Charge
{

    /**
     * @var
     */
    protected $id;

    /**
     * @var
     */
    protected $transactionId;

    /**
     * @var
     */
    protected $amount;

    /**
     * @var
     */
    protected $currency;

    /**
     * @var
     */
    protected $description;

    /**
     * @var
     */
    protected $state;

    /**
     * @var
     */
    protected $code;

    /**
     * @var
     */
    protected $createdAt;

    /**
     * Charge constructor.
     * @param $id
     * @param $transactionId
     * @param $amount
     * @param $currency
     * @param $description
     * @param $state
     * @param $code
     * @param $createdAt
     */
    public function __construct($id, $transactionId, $amount, $currency, $description, $state, $code, $createdAt)
    {
        $this->id            = $id;
        $this->transactionId = $transactionId;
        $this->amount        = $amount;
        $this->currency      = $currency;
        $this->description   = $description;
        $this->state         = $state;
        $this->code          = $code;
        $this->createdAt     = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function isSuccessful()
    {
        return '00' === $this->code;
    }
}