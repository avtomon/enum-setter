<?php

namespace Scaleplan\EnumSetter;

/**
 * Class ConnectionStructure
 *
 * @package Scaleplan\EnumSetter
 */
final class ConnectionStructure
{
    /**
     * @var string;
     */
    private $dns;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $defaultSchema = 'public';

    /**
     * @return string
     */
    public function getDns() : string
    {
        return $this->dns;
    }

    /**
     * @param string $dns
     */
    public function setDns(string $dns) : void
    {
        $this->dns = $dns;
    }

    /**
     * @return string
     */
    public function getUser() : string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user) : void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getDefaultSchema() : string
    {
        return $this->defaultSchema;
    }

    /**
     * @param string $defaultSchema
     */
    public function setDefaultSchema(string $defaultSchema) : void
    {
        $this->defaultSchema = $defaultSchema;
    }
}