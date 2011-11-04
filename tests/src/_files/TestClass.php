<?php
class TestClass
{
    const FOO = 1;
    /**
     * DocBlock
     */
    static public function foo() {}
    static public function fn1() {}
    public function fn2() {}
    public function fn3(TestClass $testClass) {}
    public function fn4($bar = TestClass::FOO) {}
    public function fn5(&$bar) {}
    public function fn6(array $foo) {}
    public function fn7(array $foo = array()) {}
    public function fn8($foo = array()) {}
}