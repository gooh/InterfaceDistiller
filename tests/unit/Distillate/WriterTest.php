<?php
namespace com\github\gooh\InterfaceDistiller\Tests\Distillate;
use \com\github\gooh\InterfaceDistiller\Distillate\Writer as Writer;
require __DIR__ . '/_files/TestClass.php';

/**
 * @covers \com\github\gooh\InterfaceDistiller\Distillate\Writer::<!public>
 * @covers \com\github\gooh\InterfaceDistiller\Distillate\Writer::__construct
 */
class WriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \com\github\gooh\InterfaceDistiller\Distillate\Writer
     */
    private $writer;

    /**
     * @var \SplFileObject
     */
    private $fileObject;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->fileObject = new \SplTempFileObject(-1);
        $this->writer = new Writer($this->fileObject);
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate\Writer::writeToFile
     */
    public function testWritingInitialStateWritesMinimalInterface()
    {
        $accessors = $this->stubInterfaceAccessors('TestInterface');

        $this->writer->writeToFile($accessors);
        $this->assertSameContentAtLine('<?php', 0);
        $this->assertSameContentAtLine('interface TestInterface', 1);
        $this->assertSameContentAtLine('{', 2);
        $this->assertSameContentAtLine('}', 3);
    }

    /**
     * @param string $interfaceName
     * @param string $extendingInterfaces
     * @param array $interfaceMethods
     * @return InterfaceAccessors
     */
    private function stubInterfaceAccessors($interfaceName, $extendingInterfaces = '', $interfaceMethods = array())
    {
        $baseNamespace = '\\com\\github\\gooh\\InterfaceDistiller\\Distillate\\';
        $accessors = $this->getMock($baseNamespace . 'Accessors');
        $accessors
            ->expects($this->any())
            ->method('getInterfaceName')
            ->will($this->returnValue($interfaceName));
        $accessors
            ->expects($this->any())
            ->method('getExtendingInterfaces')
            ->will($this->returnValue($extendingInterfaces));
        $accessors
            ->expects($this->any())
            ->method('getInterfaceMethods')
            ->will($this->returnValue($interfaceMethods));

        return $accessors;
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate\Writer::writeToFile
     */
    public function testWrittenInterfaceContainsAllExtendedInterfaceNames()
    {
        $accessors = $this->stubInterfaceAccessors('TestInterface', 'Foo, Bar');
        $this->writer->writeToFile($accessors);
        $this->assertSameContentAtLine('interface TestInterface extends Foo, Bar', 1);
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate\Writer::writeToFile
     * @dataProvider provideReflectionMethodsAndExpectedSignatures
     * @param \ReflectionMethod $method
     * @param string $expectedSignature
     */
    public function testWrittenInterfaceContainsAddedMethods(\ReflectionMethod $method, $expectedSignature)
    {
        $accessors = $this->stubInterfaceAccessors('', '', array($method));
        $this->writer->writeToFile($accessors);
        $this->assertSameContentAtLine($expectedSignature, 3);
    }

    /**
     * @return array
     */
    public function provideReflectionMethodsAndExpectedSignatures()
    {
        $signatures = array(
        	'public static function fn1();',
        	'public function fn2();',
        	'public function fn3(' . __NAMESPACE__ . '\\TestClass $testClass);',
        	'public function fn4($bar = 1);',
        	'public function fn5(&$bar);',
        	'public function fn6(array $foo);',
        	'public function fn7(array $foo = array());',
        	'public function fn8($foo = array());',
        	'public function fn9(\\DateTime $dateTime);'
        );
        foreach ($signatures as $i => $signature) {
            $data[] = array(
                new \ReflectionMethod(__NAMESPACE__ . '\\TestClass', 'fn' . ($i + 1)),
                $signature
            );
        }
        return $data;
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate\Writer::writeToFile
     */
    public function testWrittenMethodsContainExistingDocBlocks()
    {
        $method = array(new \ReflectionMethod(__NAMESPACE__ . '\\TestClass', 'foo'));
        $accessors = $this->stubInterfaceAccessors('', '', $method);
        $this->writer->writeToFile($accessors);
        $this->assertSameContentAtLine('/**', 3);
        $this->assertSameContentAtLine('* DocBlock', 4);
        $this->assertSameContentAtLine('*/', 5);
    }

    /**
     * @param string $expectedContent
     * @param integer $zeroBasedLineNumber
     * @param boolean $trimLine
     * @return void
     */
    private function assertSameContentAtLine($expectedContent, $zeroBasedLineNumber, $trimLine = true)
    {
        $this->fileObject->seek($zeroBasedLineNumber);
        $actualContent = $this->fileObject->current();
        if ($trimLine) {
            $actualContent = trim($actualContent);
        }
        $this->assertSame($expectedContent, $actualContent);
    }
}