# InterfaceDistiller

InterfaceDistiller allows you to derive Interfaces from the API of a class.

## Basic Usage Example

```php
<?php
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
    ->filterMethodsByPattern('(^f)')
    ->saveAs(new SplFileObject('MyInterface.php'))
    ->distill();
```        
Additional executable examples can be found in the examples folder.
