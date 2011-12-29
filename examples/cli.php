#!/usr/bin/php -q
<?php
namespace com\github\gooh\InterfaceDistiller;
include __DIR__ . '/../src/autoload.php';
call_user_func(
    new Controller\CommandLine(new InterfaceDistiller),
    $argv,
    new \SplFileObject('php://stdout')
);
