<?php

declare(strict_types = 1);

namespace Gorky\Espago\Model\Response;

class CvvToken
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $validTo;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var bool
     */
    private $used;

    /**
     * @param string $id
     * @param int $validTo
     * @param int $createdAt
     * @param bool $used
     */
    public function __construct($id, int $validTo, int $createdAt, $used)
    {
        $this->id = $id;
        $this->validTo = (new \DateTime())->setTimestamp($validTo);
        $this->createdAt = (new \DateTime())->setTimestamp($createdAt);
        $this->used = $used;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getValidTo(): \DateTime
    {
        return $this->validTo;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->used;
    }
}