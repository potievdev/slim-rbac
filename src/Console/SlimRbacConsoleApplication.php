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
    public function __construct(string $version = '1.0.0')
    {
        parent::__construct('https://github.com/potievdev/slim_rbac?v=' . $version);

        $this->addCommands([
            new Command\CreateConfigCommand(),
            new Command\MigrateDatabaseCommand(),
            new Command\RollbackDatabaseCommand(),
        ]);
    }

    /**
     * Runs the current application.
     *
     * @param InputInterface $input An Input instance
     * @param OutputInterface $output An Output instance
     * @return integer 0 if everything went fine, or an error code
     * @throws \Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output): int
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
