<?php

namespace Gorky\Espago\Response;

use Gorky\Espago\Model\Card;

class ChargeResponse
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $channel;

    /**
     * @var integer
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $client;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var Card
     */
    private $card;

    /**
     * @var string
     */
    private $issuerResponseCode;

    /**
     * @var string
     */
    private $rejectReason;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * @var bool
     */
    private $is3dSecure;

    /**
     * @var bool
     */
    private $reversable;

    /**
     * @var array
     */
    private $tdsRedirectForm;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string
     *
     * @return ChargeResponse
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     *
     * @return ChargeResponse
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return ChargeResponse
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return ChargeResponse
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return ChargeResponse
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $client
     *
     * @return ChargeResponse
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return ChargeResponse
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param Card $card
     *
     * @return ChargeResponse
     */
    public function setCard(Card $card)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * @return string
     */
    public function getIssuerResponseCode()
    {
        return $this->issuerResponseCode;
    }

    /**
     * @param string $issuerResponseCode
     *
     * @return ChargeResponse
     */
    public function setIssuerResponseCode($issuerResponseCode)
    {
        $this->issuerResponseCode = $issuerResponseCode;

        return $this;
    }

    /**
     * @param string $rejectReason
     *
     * @return $this
     */
    public function setRejectReason(string $rejectReason)
    {
        $this->rejectReason = $rejectReason;

        return $this;
    }

    /**
     * @return string
     */
    public function getRejectReason(): string
    {
        return $this->rejectReason;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     *
     * @return ChargeResponse
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return ChargeResponse
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIs3dSecure()
    {
        return $this->is3dSecure;
    }

    /**
     * @param boolean $is3dSecure
     *
     * @return ChargeResponse
     */
    public function setIs3dSecure($is3dSecure)
    {
        $this->is3dSecure = $is3dSecure;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isReversable()
    {
        return $this->reversable;
    }

    /**
     * @param boolean $reversable
     *
     * @return ChargeResponse
     */
    public function setReversable($reversable)
    {
        $this->reversable = $reversable;

        return $this;
    }

    /**
     * @return array
     */
    public function getTdsRedirectForm()
    {
        return $this->tdsRedirectForm;
    }

    /**
     * @param array $tdsRedirectForm
     *
     * @return ChargeResponse
     */
    public function setTdsRedirectForm($tdsRedirectForm)
    {
        $this->tdsRedirectForm = $tdsRedirectForm;

        return $this;
    }
}