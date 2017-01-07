<?php

declare(strict_types = 1);

namespace Gorky\Espago\Model;

/**
 * Todo: Perfect candidate for Immutable class: https://wiki.php.net/rfc/immutability
 */
class ApiCredentials
{
    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $appId
     * @param string $publicKey
     * @param string $password
     */
    public function __construct(string $appId, string $publicKey, string $password)
    {
        $this->appId = $appId;
        $this->publicKey = $publicKey;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}