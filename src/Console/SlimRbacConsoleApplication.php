<?php

namespace Potievdev\SlimRbac\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SlimRbacConsoleApplication extends Application
{
    /**
     * Class Constructor.
     *
     * Initialize the SlimRbac console application.
     *
     * @param string $version The Application Version
     */
    public function __construct($version = '0.0.1')
    {
        parent::__construct('https://github.com/potievdev/slim-rbac?v=' . $version);

        $this->addCommands([
            new Command\MigrateCommand(),
            new Command\RollbackCommand(),
        ]);
    }

    /**
     * Runs the current application.
     *
     * @param InputInterface $input An Input instance
     * @param OutputInterface $output An Output instance
     * @return integer 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        // always show the version information except when the user invokes the help
        // command as that already does it
        if (false === $input->hasParameterOption(['--help', '-h']) && null !== $input->getFirstArgument()) {
            $output->writeln($this->getLongVersion());
            $output->writeln('Slim Role Based Access Control Middleware');
            $output->writeln('');
        }

        return parent::doRun($input, $output);
    }
}