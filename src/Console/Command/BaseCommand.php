<?php

namespace Potievdev\SlimRbac\Console\Command;

use Symfony\Component\Console\Command\Command;
use Phinx\Config\Config;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BaseCommand
 * @package Potievdev\SlimRbac\Command
 */
class BaseCommand extends Command
{
    /** Default environment name */
    const DEFAULT_ENVIRONMENT_NAME = 'rb';

    /** Default migrations table name */
    const DEFAULT_MIGRATION_TABLE = 'migrations';

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
     * @throws \Exception
     */
    private function getCliPath(InputInterface $input)
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

        throw new \Exception(
            'There is not found config file.' . PHP_EOL .
            'You can pass path to file as option: -c FILE_PATH'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = require $this->getCliPath($input);

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $entityManager->getConnection();

        $paths['migrations'] = __DIR__ . '/../../migrations';

        $environments['default_migration_table'] = $this->migrationTableName;

        $environments['default_database'] = $this->environment;

        $environments[$this->environment] = [
            'adapter'    => $connection->getDriver()->getDatabasePlatform()->getName(),
            'host'       => $connection->getHost(),
            'name'       => $connection->getDatabase(),
            'user'       => $connection->getUsername(),
            'pass'       => $connection->getPassword()
        ];

        $configArray['paths'] = $paths;
        $configArray['environments'] = $environments;

        $this->config = new Config($configArray);
    }
}