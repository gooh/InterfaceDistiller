<?php
namespace com\github\gooh\InterfaceDistiller;

class DistillTestClass extends DistillTestClassBaseClass
{
    /**
     * @return void
     */
    public function __construct() {}
    public function publicFunction() {}
    public function publicFunctionWithParameters($a, $b, \stdClass $c, &$d, $e=array(1, 2, 3)) {}
    protected function protectedFunction() {}
    public static function publicStaticFunction() {}
    protected static function protectedStaticFunction() {}
    public function __call($method, $args) {}
}

class DistillTestClassBaseClass
{
    public function publicBaseClassFunction() {}
}
