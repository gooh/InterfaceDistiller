
#!/usr/bin/env php
<?php
require __DIR__.'/../src/autoload.php';
call_user_func(
new com\github\gooh\InterfaceDistiller\Controller\CommandLine(
new com\github\gooh\InterfaceDistiller\InterfaceDistiller
),
$argv,
new SplFileObject('php://stdout')
);