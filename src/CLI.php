<?php
namespace com\github\gooh\InterfaceDistiller;
require 'autoload.php';
call_user_func_array(
    new Controller\CommandLine(new InterfaceDistiller),
    array($argv, STDOUT)
);