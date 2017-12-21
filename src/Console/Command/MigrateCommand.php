<?php

namespace Potievdev\SlimRbac\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Phinx\Migration\Manager;

/**
 * Class MigrateCommand
 * @package Potievdev\SlimRbac\Command
 */
class MigrateCommand extends BaseCommand
{
    public function configure()
    {
        $this
            ->setName('migrate')
            ->setDescription('Applies migrations to database')
            ->setDefinition([
                new InputOption('--config', '-c', InputOption::VALUE_OPTIONAL, 'Path for file which initialized connection to db and return it in helper set component')
            ]);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        $manager = new Manager($this->config, $input, $output);
        $manager->migrate($this->config->getDefaultEnvironment());
    }
}