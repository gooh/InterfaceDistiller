# InterfaceDistiller

InterfaceDistiller allows you to derive Interfaces from the API of a class.

## Basic Usage Example

Interface Distiller can either be used a) programmatically, b) via a Builder or c) via the 
command line. Using it programmatically offers the greatest flexibility but is usually not 
needed. The Builder offers a convenient way to configure distillation of interfaces. The 
command line interface is a wrapper around the Builder and offers the same options. 

### Example 1 - Using the Builder

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

With the notable exception of the `distill` and `reset` method, all other public methods 
provide a Fluent Interface. Calling `distill` will create a new interface from the specified 
class and given configuration. The source class is not altered in any way (it's not extraction). 
Subsequent calls to `distill` will use the same configuration unless `reset` was called.

### Example 2 - Using the Commandline Interface

```
    Interface Distiller 1.0.0

    Usage: phpdistill [options] <classname> <interfacename>

      --methodsWithModifiers <number>       A ReflectionMethod Visibility BitMask. Defaults to Public.
      --extendInterfaceFrom  <name,...>     Comma-separated list of Interfaces to extend.
      --excludeImplementedMethods           Will exclude all implemented methods.
      --excludeInheritedMethods             Will exclude all inherited methods.
      --excludeMagicMethods                 Will exclude all magic methods.
      --excludeOldStyleConstructors         Will exclude Legacy Constructors.
      --filterMethodsByPattern <pattern>    Only include methods matching PCRE pattern.
      --saveAs                              Filename to save new Interface to. STDOUT if omitted.
```

The Commandline Interface will always reset configuration between calls. This means you need 
to specify the full configuration for each class you want to distill interfaces from.

### Example 3 - Using the progammatic approach

A programmatic example can be found in the examples folder. In general, you will only need 
this approach if you need to add, modify or swap out internal components.
