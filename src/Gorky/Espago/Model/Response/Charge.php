<?php

declare(strict_types = 1);

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
     * @var float
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
    private $is3dSecure = false;

    /**
     * @var bool
     */
    private $reversable = false;

    /**
     * @var array
     */
    private $tdsRedirectForm;

    /**
     * @var string
     */
    private $multiCurrencyIndicator;

    /**
     * @var DccDecision
     */
    private $dccDecision;

    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Charge
     */
    public function setDescription(string $description): Charge
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     *
     * @return Charge
     */
    public function setChannel(string $channel): Charge
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return Charge
     */
    public function setAmount(float $amount): Charge
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return Charge
     */
    public function setCurrency(string $currency): Charge
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return Charge
     */
    public function setState(string $state): Charge
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     *
     * @return Charge
     */
    public function setClientId(string $clientId): Charge
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Charge
     */
    public function setCreatedAt(\DateTime $createdAt): Charge
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getIssuerResponseCode(): string
    {
        return $this->issuerResponseCode;
    }

    /**
     * @param string $issuerResponseCode
     *
     * @return Charge
     */
    public function setIssuerResponseCode(string $issuerResponseCode): Charge
    {
        $this->issuerResponseCode = $issuerResponseCode;

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
     * @param string $rejectReason
     *
     * @return Charge
     */
    public function setRejectReason(string $rejectReason): Charge
    {
        $this->rejectReason = $rejectReason;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     *
     * @return Charge
     */
    public function setTransactionId(string $transactionId): Charge
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     *
     * @return Charge
     */
    public function setRedirectUrl(string $redirectUrl): Charge
    {
        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    /**
     * @return boolean
     */
    public function is3dSecure(): bool
    {
        return $this->is3dSecure;
    }

    /**
     * @param boolean $is3dSecure
     *
     * @return Charge
     */
    public function set3dSecure(bool $is3dSecure): Charge
    {
        $this->is3dSecure = $is3dSecure;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isReversable(): bool
    {
        return $this->reversable;
    }

    /**
     * @param boolean $reversable
     *
     * @return Charge
     */
    public function setReversable(bool $reversable): Charge
    {
        $this->reversable = $reversable;

        return $this;
    }

    /**
     * @return array
     */
    public function getTdsRedirectForm(): array
    {
        return $this->tdsRedirectForm;
    }

    /**
     * @param array $tdsRedirectForm
     *
     * @return Charge
     */
    public function setTdsRedirectForm(array $tdsRedirectForm): Charge
    {
        $this->tdsRedirectForm = $tdsRedirectForm;

        return $this;
    }

    /**
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * @param Card $card
     *
     * @return Charge
     */
    public function setCard(Card $card): self
    {
        $this->card = $card;

        return $this;
    }

    /**
     * @return string
     */
    public function getMultiCurrencyIndicator(): string
    {
        return $this->multiCurrencyIndicator;
    }

    /**
     * @param string $multiCurrencyIndicator
     *
     * @return Charge
     */
    public function setMultiCurrencyIndicator(string $multiCurrencyIndicator): Charge
    {
        $this->multiCurrencyIndicator = $multiCurrencyIndicator;

        return $this;
    }

    /**
     * @return DccDecision
     */
    public function getDccDecision(): DccDecision
    {
        return $this->dccDecision;
    }

    /**
     * @param DccDecision $dccDecision
     *
     * @return Charge
     */
    public function setDccDecision(DccDecision $dccDecision): Charge
    {
        $this->dccDecision = $dccDecision;

        return $this;
    }

    /**
     * @return bool
     */
    public function mustMakeDccDecision()
    {
        return self::PAYMENT_STATUS_DCC_DECISION === $this->state;
    }
}