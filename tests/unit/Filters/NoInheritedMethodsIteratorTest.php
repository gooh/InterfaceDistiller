<?php
namespace com\github\gooh\InterfaceDistiller\Tests\Filters;

class NoInheritedMethodsIteratorTest extends FilterIteratorTestCase
{
    public function testIteratorWillFilterInheritedMethods()
    {
        $this->assertFilterIteratorContains(
            $this->addTraitMethodWhenSupported(
                array(
                    $this->getTestClassMethod('foo'),
                    $this->getTestClassMethod('__construct'),
                    $this->gettestClassMethod('TestClass'),
                    $this->getTestClassMethod('implementedMethod')
                )
            )
        );
    }

    /**
     * @see FilterIteratorTestCase::createFilterIterator()
     */
    protected function createFilterIterator(\Iterator $methodIterator)
    {
        return new \com\github\gooh\InterfaceDistiller\Filters\NoInheritedMethodsIterator(
            $methodIterator,
            new \ReflectionClass($this->getTestClassName())
        );
    }
}