<?php
namespace com\github\gooh\InterfaceDistiller;
include __DIR__ . '/../src/autoload.php';
$distiller = new InterfaceDistiller;
$distiller
    ->methodsWithModifiers(\ReflectionMethod::IS_PUBLIC)
    ->extendInterfaceFrom('Iterator, SeekableIterator')
    ->excludeImplementedMethods()
    ->excludeInheritedMethods()
    ->excludeMagicMethods()
    ->excludeOldStyleConstructors()
    ->filterMethodsByPattern('(^get)')
    ->saveAs('php://output')
    ->distill('SomeFoo', 'MyInterface');