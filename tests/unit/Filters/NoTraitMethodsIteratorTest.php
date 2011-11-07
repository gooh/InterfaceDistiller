<?php
namespace com\github\gooh\InterfaceDistiller\Tests\Filters;
/**
 * @covers \com\github\gooh\InterfaceDistiller\Filters\NoTraitMethodsIterator
 */
class NoTraitMethodsIteratorTest extends FilterIteratorTestCase
{
    /**
     * @requires PHP 5.4
     */
    public function testIteratorWillFilterTraitMethods()
    {
        return;
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
