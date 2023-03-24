<?php

namespace Potievdev\SlimRbac\Component\Config;

use Potievdev\SlimRbac\Exception\ConfigNotFoundException;

class RbacConfig
{
    public const ATTRIBUTE_RESOURCE_TYPE = 'attribute';
    public const HEADER_RESOURCE_TYPE = 'header';
    public const COOKIE_RESOURCE_TYPE = 'cookie';

    /** @var string */
    private $databaseDriver;

    /** @var string */
    private $databaseHost;

    /** @var string */
    private $databaseUser;

    /** @var string */
    private $databasePassword;

    /** @var int */
    private $databasePort;

    /** @var string */
    private $databaseName;

    /** @var string */
    private $databaseCharset;

    /** @var string */
    private $userIdFieldName;

    /** @var string */
    private $userIdResourceType;

    /**
     * @param string $databaseDriver
     * @param string $databaseHost
     * @param string $databaseUser
     * @param string $databasePassword
     * @param int $databasePort
     * @param string $databaseName
     * @param string $databaseCharset
     * @param string $userIdFieldName
     * @param string $userIdResourceType
     */
    public function __construct(
        string $databaseDriver,
        string $databaseHost,
        string $databaseUser,
        string $databasePassword,
        int $databasePort,
        string $databaseName,
        string $databaseCharset,
        string $userIdFieldName,
        string $userIdResourceType
    ) {
        $this->databaseDriver = $databaseDriver;
        $this->databaseHost = $databaseHost;
        $this->databaseUser = $databaseUser;
        $this->databasePassword = $databasePassword;
        $this->databasePort = $databasePort;
        $this->databaseName = $databaseName;
        $this->databaseCharset = $databaseCharset;
        $this->userIdFieldName = $userIdFieldName;
        $this->userIdResourceType = $userIdResourceType;
    }

    /**
     * @throws ConfigNotFoundException
     */
    public static function createFromConfigFile(): RbacConfig
    {
        return self::createFromConfigs(RbacConfigLoader::loadConfigs());
    }

    public static function createFromConfigs(array $configs): RbacConfig
    {
        return new self(
            $configs['database']['driver'],
            $configs['database']['host'],
            $configs['database']['user'],
            $configs['database']['password'],
            $configs['database']['port'],
            $configs['database']['dbname'],
            $configs['database']['charset'],
            $configs['userId']['fieldName'],
            $configs['userId']['resourceType'],
        );
    }

    /**
     * @return string
     */
    public function getDatabaseDriver(): string
    {
        return $this->databaseDriver;
    }

    /**
     * @return string
     */
    public function getDatabaseHost(): string
    {
        return $this->databaseHost;
    }

    /**
     * @return string
     */
    public function getDatabaseUser(): string
    {
        return $this->databaseUser;
    }

    /**
     * @return string
     */
    public function getDatabasePassword(): string
    {
        return $this->databasePassword;
    }

    /**
     * @return int
     */
    public function getDatabasePort(): int
    {
        return $this->databasePort;
    }

    /**
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    /**
     * @return string
     */
    public function getDatabaseCharset(): string
    {
        return $this->databaseCharset;
    }

    /**
     * @return string
     */
    public function getUserIdFieldName(): string
    {
        return $this->userIdFieldName;
    }

    /**
     * @return string
     */
    public function getUserIdResourceType(): string
    {
        return $this->userIdResourceType;
    }

}