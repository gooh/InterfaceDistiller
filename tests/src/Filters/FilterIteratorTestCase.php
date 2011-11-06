<?php
namespace com\github\gooh\InterfaceDistiller\Tests\Filters;

version_compare(phpversion(), '5.4', '<')
    ? require_once __DIR__ . '/_files/TestClass.php'
    : require_once __DIR__ . '/_files/TestClassWithTrait.php';

abstract class FilterIteratorTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $filterIteratorClassName;

    /**
     * @var string
     */
    private $testClassName;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     * @return void
     */
    public function setUp()
    {
        $this->filterIteratorClassName = str_replace(
            __NAMESPACE__,
            '\\com\\github\\gooh\\InterfaceDistiller\\Filters',
            substr(get_class($this), 0, -4)
        );
        $this->testClassName = __NAMESPACE__ . '\\TestClass';

    }

    /**
     * @param string $methodNames
     * @return \ReflectionMethod
     */
    public function getTestClassMethod($methodName)
    {
        return new \ReflectionMethod($this->testClassName, $methodName);
    }

    /**
     * @param array $methods
     * @return array
     */
    public function addTraitMethodWhenSupported(array $methods)
    {
        return version_compare(phpversion(), '5.4', '>=')
            ? $methods[] = $this->getTestClassMethod('traitMethod')
            : $methods;
    }

    /**
     * @param array $reflectionMethods
     * @return void
     */
    public function assertFilterIteratorContains(array $reflectionMethods)
    {
        $actualResults = $this->getFilteredArray();
        sort($reflectionMethods);
        sort($actualResults);

        $this->assertEquals($reflectionMethods, $actualResults);
    }

    /**
     * @return array
     */
    private function getFilteredArray()
    {
        $reflector = new \ReflectionClass($this->testClassName);
        return iterator_to_array(
            $this->createFilterIterator(
                new \ArrayIterator($reflector->getMethods())
            ),
            false
        );
    }

    /**
     * @param \Iterator $methodIterator
     * @return \FilterIterator
     */
    protected function createFilterIterator(\Iterator $methodIterator)
    {
        return new $this->filterIteratorClassName($methodIterator);
    }

    /**
     * @return string
     */
    public function getTestClassName()
    {
        return $this->testClassName;
    }
}