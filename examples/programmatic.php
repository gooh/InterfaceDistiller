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
$writer = new InterfaceWriter('FileObject');
$writer->setExtendingInterfaces('Iterator, SeekableIterator');
foreach ($methodIterator as $method) {
    $writer->addMethod($method);
}
$file = $writer->writeToFile(new SplTempFileObject(-1));
$file->rewind();
$file->fpassthru();

