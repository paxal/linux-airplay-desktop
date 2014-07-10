#!/usr/bin/php
<?php

require_once(__DIR__.'/../vendor/autoload.php');

$buildDirectory = __DIR__.'/../build';
if (!is_dir($buildDirectory)) {
    mkdir($buildDirectory);
}

$phar_path = $buildDirectory.'/airplay.phar';

$phar = new Phar(
    $phar_path,
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME,
    'airplay.phar'
);

$phar->startBuffering();

//$phar->buildFromDirectory(__DIR__.'/../', '@^(src|vendor)/.*\.(php|jar)$@');
$phar->buildFromDirectory(__DIR__.'/../', '@(src|vendor)/.*\.(php|jar)@');
$defaultStub = $phar->createDefaultStub('src/lib/console.php');
$stub = "#!/usr/bin/php\n".$defaultStub;
$phar->setStub($stub);

$phar->stopBuffering();

chmod($phar_path, 0755);
