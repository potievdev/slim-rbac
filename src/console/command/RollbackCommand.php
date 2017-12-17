<?php

namespace Potievdev\SlimRbac\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Phinx\Migration\Manager;

/**
 * Class RollbackCommand
 * @package Potievdev\SlimRbac\Command
 */
class RollbackCommand extends BaseCommand
{
    public function configure()
    {
        $this
            ->setName('rollback')
            ->setDescription('Rollback last migration to database')
            ->setDefinition([
                new InputOption('--cli-path', '-c', InputOption::VALUE_OPTIONAL, 'Path for file which initialized connection to db and return it in helper set component')
            ]);;
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
        $manager->rollback($this->config->getDefaultEnvironment());
    }
}