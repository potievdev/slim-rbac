<?php

namespace Potievdev\SlimRbac\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateConfigCommand
 * @package Potievdev\SlimRbac\Command
 */
class CreateConfigCommand extends Command
{
    public function configure()
    {
        $this
            ->setName('create-config')
            ->setDescription('This command creates sr_config.yaml file in working directory');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = file_get_contents(__DIR__ . '/../../../config/sr_config.example.yaml');
        $currentDir = getcwd();
        file_put_contents($currentDir . '/sr_config.yaml', $configFile);
        $output->writeln("File sr_config.yaml created in directory: $currentDir");
    }
}
