<?php
namespace com\github\gooh\InterfaceDistiller;
class CLI
{
    public function main($outputStream, $argv) {
        fwrite($outputStream, 'Usage ...' . PHP_EOL);
    }
}
