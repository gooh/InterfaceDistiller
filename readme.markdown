# InterfaceDistiller

InterfaceDistiller allows you to derive Interfaces from the API of a class.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gooh/InterfaceDistiller/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gooh/InterfaceDistiller/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/gooh/InterfaceDistiller/badges/build.png?b=master)](https://scrutinizer-ci.com/g/gooh/InterfaceDistiller/build-status/master)

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

To use the Commandline Interface, you have to run `build/create_phar.php` to build a phar file previously.
```sh
$php -d phar.readonly=0 build/create-phar.php    
```    

```
    Usage: phpdistill [options] <classname> <interfacename>

      --bootstrap                           Path to File containing your bootstrap and autoloader

      --methodsWithModifiers <number>       A ReflectionMethod Visibility BitMask. Defaults to Public.
      --extendInterfaceFrom  <name,...>     Comma-separated list of Interfaces to extend.
      --excludeImplementedMethods           Will exclude all implemented methods.
      --excludeInheritedMethods             Will exclude all inherited methods.
      --excludeMagicMethods                 Will exclude all magic methods.
      --excludeOldStyleConstructors         Will exclude Legacy Constructors.
      --filterMethodsByPattern <pattern>    Only include methods matching PCRE pattern.
      --saveAs                              Filename to save new Interface to. STDOUT if omitted.
```

The CommandLine Interface requires you to set the pathname to your bootstrap file in the
`--bootstrap` option. Your bootstrap file should contain all the necessary logic to include 
any classes you want derive interfaces from, e.g. autoloaders, include paths, etc. Without that 
you will only be able to distill from native classes.

If you installed InterfaceDistiller through the Composer package manager, the commandline tool 
will try to include the autoloader in the vendor directory.

The Commandline Interface will always reset configuration between calls. This means you need 
to specify the full configuration for each class you want to distill interfaces from.

### Example 3 - Using the progammatic approach

A programmatic example can be found in the examples folder. In general, you will only need 
this approach if you need to add, modify or swap out internal components.
