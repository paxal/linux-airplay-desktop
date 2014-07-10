<?php

require_once(__DIR__.'/../../vendor/autoload.php');

$application = new \Symfony\Component\Console\Application();
$command = new \Paxal\Airplay\RunCommand();
$application->add($command);
$application->setDefaultCommand('run');

$application->run(
    new \Symfony\Component\Console\Input\ArgvInput(),
    new \Symfony\Component\Console\Output\ConsoleOutput()
);
