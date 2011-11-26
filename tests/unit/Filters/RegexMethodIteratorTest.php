<?php
namespace com\github\gooh\InterfaceDistiller\Filters;
/**
 * @covers \com\github\gooh\InterfaceDistiller\Filters\RegexMethodIterator
 */
class RegexMethodIteratorTest extends FilterIteratorTestCase
{
    public function testIteratorWillFilterMethodsByRegex()
    {
        $this->assertFilterIteratorContains(
            $this->addTraitMethodWhenSupported(
                array(
                    $this->getTestClassMethod('foo'),
                    $this->gettestClassMethod('__construct'),
                    $this->getTestClassMethod('TestClass'),
                    $this->getTestClassMethod('SomeClass'),
                )
            )
        );
    }
    /**
     * @see FilterIteratorTestCase::createFilterIterator()
     */
    protected function createFilterIterator(\Iterator $methodIterator)
    {
        return new \com\github\gooh\InterfaceDistiller\Filters\RegexMethodIterator(
            $methodIterator,
            '(^[^i].*)'
        );
    }
}
