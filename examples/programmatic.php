<?php
namespace com\github\gooh\InterfaceDistiller;
include '../src/autoload.php';

$reflector = new \ReflectionClass('SplFileObject');

$methodIterator = new Filters\RegexMethodIterator(
    new Filters\NoImplementedMethodsIterator(
        new Filters\NoInheritedMethodsIterator(
            new Filters\NoOldStyleConstructorIterator(
                new Filters\NoMagicMethodsIterator(
                    new \ArrayIterator($reflector->getMethods())
                )
            ),
            $reflector
        )
    ),
	'(^[f].*)'
);

$distillate = new Distillate;
$distillate->setInterfaceName('MyInterface');
$distillate->setExtendingInterfaces('Iterator, SeekableIterator');
foreach ($methodIterator as $method) {
    $distillate->addMethod($method);
}

$file = new \SplTempFileObject(-1);
$writer = new Distillate\Writer($file);
$writer->writeToFile($distillate);
$file->rewind();
$file->fpassthru();

