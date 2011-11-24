<?php
/**
 * @covers \com\github\gooh\InterfaceDistiller\Filters\NoOldStyleConstructorIterator
 */
namespace com\github\gooh\InterfaceDistiller\Tests\Filters;

class NoOldStyleConstructorIteratorTest extends FilterIteratorTestCase
{
    public function testIteratorWillFilterOldStyleConstructorMethods()
    {
        require_once __DIR__ . '/_files/TestClassWithoutNamespace.php';
        $this->setTestClassName('\\TestClass');

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

    public function testIteratorWillNotFilterOldStyleConstructorMethodsInNamespacedClasses()
    {
        $this->assertFilterIteratorContains(
            $this->addTraitMethodWhenSupported(
                array(
                    $this->getTestClassMethod('foo'),
                    $this->gettestClassMethod('__construct'),
                    $this->getTestClassMethod('TestClass'),
                    $this->getTestClassMethod('SomeClass'),
                    $this->getTestClassMethod('implementedMethod'),
                    $this->getTestClassMethod('inheritedMethod')
                )
            )
        );
    }
}
