<?php
namespace com\github\gooh\InterfaceDistiller;
include '../src/autoload.php';
$outFile = new \SplTempFileObject(-1);
$distiller = new InterfaceDistiller;
$distiller
    ->methodsWithModifiers(\ReflectionMethod::IS_PUBLIC)
    ->extendInterfaceFrom('Iterator, SeekableIterator')
    ->excludeImplementedMethods()
    ->excludeInheritedMethods()
    ->excludeMagicMethods()
    ->excludeOldStyleConstructors()
    ->filterMethodsByPattern('(^get)')
    ->saveAs($outFile)
    ->distill('SomeFoo', 'MyInterface');

$outFile->rewind();
$outFile->fpassthru();
