# InterfaceDistiller

InterfaceDistiller allows you to derive Interfaces from the API of a class.

## Basic Usage Example

```php
<?php
$distiller = new InterfaceDistiller;
$distiller
    ->methodsWithModifiers(\ReflectionMethod::IS_PUBLIC)
    ->extendInterfaceFrom('Iterator, SeekableIterator')
    ->excludeImplementedMethods()
    ->excludeInheritedMethods()
    ->excludeMagicMethods()
    ->excludeOldStyleConstructors()
    ->filterMethodsByPattern('(^get)')
    ->saveAs(new SplFileObject('MyInterface.php'))
    ->distill('SomeFoo', 'MyInterface');
```        
Additional executable examples can be found in the examples folder.
