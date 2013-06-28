<?php

namespace Runner\Document;

use \Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Proxy
 * @ODM\Document(db="mosbot", collection="proxy")
 * @ODM\UniqueIndex(keys={"ip"="asc", "port"="asc"})
 * @package Runner\Document
 */
class Proxy
{
    /** @ODM\Id(strategy="AUTO") */
    private $id;

    /** @ODM\Field(type="timestamp") */
    private $firstUsed;
    /** @ODM\Field(type="timestamp") */
    private $lastUsed;

    /** @ODM\Field(type="float") */
    private $speed;
    /** @ODM\Field(type="float") */
    private $uptime;
    /** @ODM\Field(type="int") */
    private $succeedChecks;
    /** @ODM\Field(type="int") */
    private $failedChecks;

    /** @ODM\Field(type="string") */
    private $ip;
    /** @ODM\Field(type="int") */
    private $port;

    /**
     * @param mixed $failedChecks
     */
    public function setFailedChecks($failedChecks)
    {
        $this->failedChecks = $failedChecks;
    }

    /**
     * @return mixed
     */
    public function getFailedChecks()
    {
        return $this->failedChecks;
    }

    /**
     * @param mixed $firstUsed
     */
    public function setFirstUsed($firstUsed)
    {
        $this->firstUsed = $firstUsed;
    }

    /**
     * @return mixed
     */
    public function getFirstUsed()
    {
        return $this->firstUsed;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $lastUsed
     */
    public function setLastUsed($lastUsed)
    {
        $this->lastUsed = $lastUsed;
    }

    /**
     * @return mixed
     */
    public function getLastUsed()
    {
        return $this->lastUsed;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $speed
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }

    /**
     * @return mixed
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param mixed $succeedChecks
     */
    public function setSucceedChecks($succeedChecks)
    {
        $this->succeedChecks = $succeedChecks;
    }

    /**
     * @return mixed
     */
    public function getSucceedChecks()
    {
        return $this->succeedChecks;
    }

    /**
     * @param mixed $uptime
     */
    public function setUptime($uptime)
    {
        $this->uptime = $uptime;
    }

    /**
     * @return mixed
     */
    public function getUptime()
    {
        return $this->uptime;
    }


}