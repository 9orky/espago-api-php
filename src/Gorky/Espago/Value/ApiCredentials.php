<?php

namespace Gorky\Espago\Value;

class ApiCredentials
{
    /**
     * @var
     */
    protected $appId;

    /**
     * @var
     */
    protected $publicKey;

    /**
     * @var
     */
    protected $password;

    /**
     * @param $appId
     * @param $publicKey
     * @param $password
     */
    public function __construct($appId, $publicKey, $password)
    {
        $this->appId = $appId;
        $this->publicKey = $publicKey;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
}