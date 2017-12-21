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
            ->setDescription('This command creates sr-config.php file in working directory');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = file_get_contents(__DIR__ . '/../../../config/sr-config.example.php');
        $currentDir = getcwd();
        file_put_contents($currentDir . '/sr-config.php', $configFile);
        $output->writeln("File sr-config.php created in directory: $currentDir");
    }
}