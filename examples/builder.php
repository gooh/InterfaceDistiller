<?php
namespace com\github\gooh\InterfaceDistiller;
include '../src/autoload.php';
$outFile = new \SplTempFileObject(-1);
$distiller = new InterfaceDistiller;
$distiller
    ->distillFromClass('SplFileObject')
    ->methodsWithModifiers(\ReflectionMethod::IS_PUBLIC)
    ->intoInterface('MyInterface')
    ->extendInterfaceFrom('Iterator, SeekableIterator')
    ->excludeImplementedMethods()
    ->excludeInheritedMethods()
    ->excludeMagicMethods()
    ->excludeOldStyleConstructors()
    ->filterMethodsByPattern('(^[f].*)')
    ->saveAs($outFile)
    ->distill();

$outFile->rewind();
$outFile->fpassthru();
