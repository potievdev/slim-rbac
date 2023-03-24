<?php

namespace Potievdev\SlimRbac\Console\Command;

use Potievdev\SlimRbac\Exception\ConfigNotFoundException;
use Potievdev\SlimRbac\Exception\NotSupportedDatabaseException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Phinx\Migration\Manager;

/**
 * Class MigrateCommand
 * @package Potievdev\SlimRbac\Command
 */
class MigrateDatabaseCommand extends BaseDatabaseCommand
{
    public function configure()
    {
        $this
            ->setName('migrate')
            ->setDescription('Applies migrations to database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws ConfigNotFoundException
     * @throws NotSupportedDatabaseException
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        parent::execute($input, $output);
        $manager = new Manager($this->config, $input, $output);
        $manager->migrate($this->config->getDefaultEnvironment());
    }
}
