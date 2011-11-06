<?php
namespace com\github\gooh\InterfaceDistiller\Tests\Filters;
require_once 'FilterIteratorTestCase.php';
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
