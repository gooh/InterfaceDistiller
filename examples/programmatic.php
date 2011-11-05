<?php
include '../src/autoload.php';

$reflector = new ReflectionClass('SplFileObject');

$methodIterator = new RegexMethodIterator(
    new NoImplementedMethodsIterator(
        new NoInheritedMethodsIterator(
            new NoOldStyleConstructorIterator(
                new NoMagicMethodsIterator(
                    new ArrayIterator($reflector->getMethods())
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

$file = new SplTempFileObject(-1);
$writer = new Writer($file);
$writer->writeToFile($distillate);
$file->rewind();
$file->fpassthru();

