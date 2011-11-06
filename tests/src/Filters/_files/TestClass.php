<?php
namespace com\github\gooh\InterfaceDistiller\Tests\Filters;
abstract class TestClass extends SomeClass implements SomeInterface
{
    abstract public function foo();
    public function __construct() {}
    public function TestClass() {}
    public function implementedMethod() {}
}

class SomeClass
{
    public function SomeClass() {}
    public function inheritedMethod() {}
}

interface SomeInterface
{
    public function implementedMethod();
}