<?php
require_once 'FilterIteratorTestCase.php';
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
    protected function createFilterIterator(Iterator $methodIterator)
    {
        return new NoInheritedMethodsIterator(
            $methodIterator,
            new ReflectionClass($this->getTestClassName())
        );
    }
}