<?php

namespace Paxal\Airplay;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Launches airplay')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!JavaHelper::hasJava()) {
            throw new \RuntimeException('Java does not seem to be found on your computer.');
        }

        $output->writeln('<comment>Looking for airplay servers, please wait...</>');

        $servers = $this->getServers();

        if (count($servers) == 0) {
            $output->writeln('<error>No servers were found on your network');
            exit(1);
        }

        $choices = array();
        foreach ($servers as $i => $server) {
            $choices[$i] = sprintf(
                '%s [%s:%s]',
                $server['name'],
                $server['address'],
                $server['port']
            );
        }

        if ($input->isInteractive()) {
            /* @var $dialogHelper DialogHelper */
            $dialogHelper = $this->getHelperSet()->get('dialog');
            $choice = $dialogHelper->select(
                $output,
                'Select a server',
                $choices
            );

        } else {
            $choice = 1;
        }

        if (!isset($servers[$choice])) {
            $output->writeln('<error>Invalid answer :(');
            exit(1);
        }

        $server = $servers[$choice];
        $address = $server['address'];
        $port = $server['port'];

        $output->writeln('<info>Launching airplay to '.$server['name']);

        $this->runJar($address, $port);

        $output->writeln('');
        $output->writeln('<comment>Exiting');
    }

    protected function getServers()
    {
        return Avahi::getServerForService('_airplay._tcp');
    }

    protected function runJar($address, $port)
    {
        JavaHelper::runJar(
            __DIR__.'/../bin/airplay.jar',
            array(
                '-h',
                "$address:$port",
                '-d'
            )
        );
    }
}
