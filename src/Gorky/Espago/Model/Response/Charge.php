<?php

namespace Gorky\Espago\Model\Response;

class Charge
{
    /**
     * New payment, client's account has not been charged.
     */
    const PAYMENT_STATUS_NEW = 'new';

    /**
     * Payment executed, client's account was successfully charged.
     */
    const PAYMENT_STATUS_EXECUTED = 'executed';

    /**
     * In response of the rejected transaction, you receive reject_reason parameter.
     */
    const PAYMENT_STATUS_REJECTED = 'rejected';

    /**
     * Payment ended by failure
     */
    const PAYMENT_STATUS_FAILED = 'failed';

    /**
     * State available only if 3D-Secure is enabled. Customer is redirected to 3D-Secure page (bank/issuer site),
     * Espago gateway is waiting for returning customer.
     */
    const PAYMENT_STATUS_TDS_REDIRECTED = 'tds_redirected';

    /**
     * [State available only if DCC is enabled]. Waiting for sending by Merchant the decision,
     * about the payment currency chosen by customer.
     */
    const PAYMENT_STATUS_DCC_DECISION = 'dcc_decision';

    /**
     * Customer resigned from the autorization of payment or left payment [state available if enabled is 3D-Secure,
     * DCC and/or MasterPass]. In case of leaving transaction with state "new", "tds_redirected" or "dcc_decision"
     * (no customer action during 1,5 hour) transactions will change state to "resigned".
     */
    const PAYMENT_STATUS_RESIGNED = 'resigned';

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
    private $clientId;

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
     * @return Charge
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
     * @return Charge
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
     * @return Charge
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
     * @return Charge
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
     * @return Charge
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     *
     * @return Charge
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

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
     * @return Charge
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
     * @return Charge
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
     * @return Charge
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
     * @return Charge
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
     * @return Charge
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
     * @return Charge
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
     * @return Charge
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
     * @return Charge
     */
    public function setTdsRedirectForm($tdsRedirectForm)
    {
        $this->tdsRedirectForm = $tdsRedirectForm;

        return $this;
    }
}