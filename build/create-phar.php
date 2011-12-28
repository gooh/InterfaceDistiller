<?php

$phar = __DIR__ . '/../interfaceDistiller.phar';
if(file_exists($phar)) {
    unlink($phar);
}

$phar = new Phar($phar, 0 , 'interfaceDistiller.phar');
$phar->buildFromDirectory(__DIR__ . '/../src/');
$phar->setStub(<<<'STUB'
<?php
Phar::mapPhar('interfaceDistiller.phar');
require 'phar://interfaceDistiller.phar/autoload.php';
call_user_func(
    new com\github\gooh\InterfaceDistiller\Controller\CommandLine(
        new com\github\gooh\InterfaceDistiller\InterfaceDistiller
    ),
    $argv,
    STDOUT
);
__HALT_COMPILER();
STUB
);

