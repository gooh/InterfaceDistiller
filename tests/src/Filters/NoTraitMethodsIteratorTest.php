<?php
require_once 'FilterIteratorTestCase.php';
class NoTraitMethodsIteratorTest extends FilterIteratorTestCase
{
    /**
     * @requires PHP 5.4
     */
    public function testIteratorWillFilterTraitMethods()
    {
        $this->assertFilterIteratorContains(
            $this->addTraitMethodWhenSupported(
                array(
                    $this->getTestClassMethod('foo'),
                    $this->gettestClassMethod('__construct'),
                    $this->getTestClassMethod('TestClass'),
                    $this->getTestClassMethod('implementedMethod'),
                    $this->getTestClassMethod('inheritedMethod'),
                    $this->getTestClassMethod('SomeClass'),
                )
            )
        );
    }
}
