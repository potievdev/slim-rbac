<?php

namespace Potievdev\Structure;

/**
 * Authorization manager options structure.
 * The instance of this class accepted as argument for constructor of AuthManager
 * Class AuthManagerOptions
 * @package Potievdev\Structure
 */
class AuthManagerOptions
{
    /** Default adapter for doctrine orm */
    const MYSQL_ADAPTER = 'pdo_mysql';

    /** Default port for connecting to database */
    const MYSQL_DEFAULT_PORT = 3306;

    /** Default charset for connecting to database */
    const MYSQL_DEFAULT_CHARSET = 'utf8';

    /** @var  string */
    private $databaseAdapter = self::MYSQL_ADAPTER;

    /** @var  string */
    private $databaseName;

    /** @var  string */
    private $databaseUsername;

    /** @var  string */
    private $databasePassword;

    /** @var int  */
    private $databasePort = self::MYSQL_DEFAULT_PORT;

    /** @var  string */
    private $databaseCharset = self::MYSQL_DEFAULT_CHARSET;

    /** @var  boolean */
    private $isDevMode = false;

    /**
     * @return string
     */
    public function getDatabaseAdapter()
    {
        return $this->databaseAdapter;
    }

    /**
     * @param string $databaseAdapter
     */
    public function setDatabaseAdapter($databaseAdapter)
    {
        $this->databaseAdapter = $databaseAdapter;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * @param string $databaseName
     */
    public function setDatabaseName($databaseName)
    {
        $this->databaseName = $databaseName;
    }

    /**
     * @return string
     */
    public function getDatabaseUsername()
    {
        return $this->databaseUsername;
    }

    /**
     * @param string $databaseUsername
     */
    public function setDatabaseUsername($databaseUsername)
    {
        $this->databaseUsername = $databaseUsername;
    }

    /**
     * @return string
     */
    public function getDatabasePassword()
    {
        return $this->databasePassword;
    }

    /**
     * @param string $databasePassword
     */
    public function setDatabasePassword($databasePassword)
    {
        $this->databasePassword = $databasePassword;
    }

    /**
     * @return int
     */
    public function getDatabasePort()
    {
        return $this->databasePort;
    }

    /**
     * @param int $databasePort
     */
    public function setDatabasePort($databasePort)
    {
        $this->databasePort = $databasePort;
    }

    /**
     * @return string
     */
    public function getDatabaseCharset()
    {
        return $this->databaseCharset;
    }

    /**
     * @param string $databaseCharset
     */
    public function setDatabaseCharset($databaseCharset)
    {
        $this->databaseCharset = $databaseCharset;
    }

    /**
     * @return bool
     */
    public function getIsDevMode()
    {
        return $this->isDevMode;
    }

    /**
     * @param bool $isDevMode
     */
    public function setIsDevMode($isDevMode)
    {
        $this->isDevMode = $isDevMode;
    }

}