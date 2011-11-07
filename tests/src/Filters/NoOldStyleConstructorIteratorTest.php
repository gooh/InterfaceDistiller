<?php
namespace com\github\gooh\InterfaceDistiller\Tests\Filters;

class NoOldStyleConstructorIteratorTest extends FilterIteratorTestCase
{
    public function testIteratorWillFilterOldStyleConstructorMethods()
    {
        $this->assertFilterIteratorContains(
            $this->addTraitMethodWhenSupported(
                array(
                    $this->getTestClassMethod('foo'),
                    $this->gettestClassMethod('__construct'),
                    $this->getTestClassMethod('implementedMethod'),
                    $this->getTestClassMethod('inheritedMethod')
                )
            )
        );
    }
}
