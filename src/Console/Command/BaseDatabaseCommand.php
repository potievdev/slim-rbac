<?php

namespace Potievdev\SlimRbac\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Potievdev\SlimRbac\Exception\ConfigNotFoundException;
use Potievdev\SlimRbac\Exception\NotSupportedDatabaseException;
use Symfony\Component\Console\Command\Command;
use Phinx\Config\Config;
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

    /** @var Config  $config*/
    protected $config;

    /** @var string environment name */
    protected $environment = self::DEFAULT_ENVIRONMENT_NAME;

    /** @var string migrations table name */
    protected $migrationTableName = self::DEFAULT_MIGRATION_TABLE;

    /**
     * Returns cli-config file path
     * @param InputInterface $input
     * @return string
     * @throws ConfigNotFoundException
     */
    private function getCliPath(InputInterface $input): string
    {
        $filePaths = [
            __DIR__ . '/../../../config/sr-config.php',
            __DIR__ . '/../../../../../../sr-config.php',
            __DIR__ . '/../../../../../../config/sr-config.php',
        ];

        if ($input->hasOption('config')) {
            $filePaths[] = $input->getOption('config');
        }

        foreach ($filePaths as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        throw new ConfigNotFoundException(
            'There is not found config file.' . PHP_EOL .
            'You can pass path to file as option: -c FILE_PATH'
        );
    }

    /**
     * @throws ConfigNotFoundException
     * @throws Exception
     * @throws NotSupportedDatabaseException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $entityManager */
        $entityManager = require $this->getCliPath($input);

        $paths['migrations'] = __DIR__ . '/../../migrations';

        $environments['default_migration_table'] = $this->migrationTableName;

        $environments['default_database'] = $this->environment;

        $environments[$this->environment] = $this->getAdapterConfigs($entityManager->getConnection());

        $configArray['paths'] = $paths;
        $configArray['environments'] = $environments;

        $this->config = new Config($configArray);
    }

    /**
     * @throws Exception
     * @throws NotSupportedDatabaseException
     */
    private function getAdapterConfigs(Connection $connection): array
    {
        $platformName = $connection->getDatabasePlatform()->getName();

        switch ($platformName) {
            case 'mysql':
                $adapterName = 'mysql';
                break;
            case 'postgresql':
                $adapterName = 'pgsql';
                break;
            default:
                throw new NotSupportedDatabaseException('Not supported database platform ' . $platformName);
        }

        return [
            'adapter' => $adapterName,
            'name'    => $connection->getDatabase(),
            'host'    => $connection->getParams()['host'],
            'user'    => $connection->getParams()['user'],
            'pass'    => $connection->getParams()['password'],
            'port'    => $connection->getParams()['port']
        ];
    }
}
