<?php

namespace Potievdev\SlimRbac\Console\Command;

use Phinx\Config\Config;
use Potievdev\SlimRbac\Component\Config\RbacConfig;
use Potievdev\SlimRbac\Exception\ConfigNotFoundException;
use Potievdev\SlimRbac\Exception\NotSupportedDatabaseException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BaseCommand
 * @package Potievdev\SlimRbac\Command
 */
class BaseDatabaseCommand extends Command
{
    /** Default environment name */
    const DEFAULT_ENVIRONMENT_NAME = 'rbac';

    /** Default migrations table name */
    const DEFAULT_MIGRATION_TABLE = 'rbac_migrations';

    /** Migration files path */
    const MIGRATION_PATH = __DIR__ . '/../../../migrations';

    /** @var Config  $config */
    protected $config;

    /**
     * @throws NotSupportedDatabaseException
     * @throws ConfigNotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $rbacConfig = RbacConfig::createFromConfigFile();

        $configArray = [
            'paths' => [
                'migrations' => self::MIGRATION_PATH
            ],
            'environments' => [
                'default_migration_table' => self::DEFAULT_MIGRATION_TABLE,
                'default_database' => self::DEFAULT_ENVIRONMENT_NAME,
                self::DEFAULT_ENVIRONMENT_NAME => $this->getAdapterConfigs($rbacConfig)
            ]
        ];

        $this->config = new Config($configArray);
    }

    /**
     * @throws NotSupportedDatabaseException
     */
    private function getAdapterConfigs(RbacConfig $rbacConfig): array
    {
        $platformName = $rbacConfig->getDatabaseDriver();

        switch ($platformName) {
            case 'pdo_mysql':
                $adapterName = 'mysql';
                break;
            case 'pdo_postgres':
                $adapterName = 'pgsql';
                break;
            default:
                throw NotSupportedDatabaseException::notSupportedPlatform($platformName);
        }

        return [
            'adapter' => $adapterName,
            'name'    => $rbacConfig->getDatabaseName(),
            'host'    => $rbacConfig->getDatabaseHost(),
            'user'    => $rbacConfig->getDatabaseUser(),
            'pass'    => $rbacConfig->getDatabasePassword(),
            'port'    => $rbacConfig->getDatabasePort(),
            'charset' => $rbacConfig->getDatabaseCharset(),
        ];
    }
}
