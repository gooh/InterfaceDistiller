<?php
namespace com\github\gooh\InterfaceDistiller\Filters;
abstract class TestClass extends SomeClass implements SomeInterface
{
    use SomeTrait;
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

trait SomeTrait {
    public function traitMethod() {}
}
