<?php

require __DIR__ . '/_files/TestClass.php';

/**
 * @covers InterfaceWriter::InterfaceWriter
 */
class InterfaceWriterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var InterfaceWriter
     */
    private $interfaceWriter;

    /**
     * @var SplFileObject
     */
    private $fileObject;

    /**
     * @return void
     */
    public function setup()
    {
        $this->fileObject = new SplTempFileObject(-1);
        $this->interfaceWriter = new InterfaceWriter('TestInterface', $this->fileObject);
    }

    /**
     * @covers InterfaceWriter::writeToFile
     * @covers InterfaceWriter::__construct
     * @covers InterfaceWriter::<!public>
     */
    public function testWritingInitialStateWritesMinimalInterface()
    {
        $this->interfaceWriter->writeToFile();
        $this->assertSameContentAtLine('<?php', 0);
        $this->assertSameContentAtLine('interface TestInterface', 1);
        $this->assertSameContentAtLine('{', 2);
        $this->assertSameContentAtLine('}', 3);
    }

    /**
     * @covers InterfaceWriter::setExtendingInterfaces
     * @covers InterfaceWriter::writeToFile
     * @covers InterfaceWriter::__construct
     * @covers InterfaceWriter::<!public>
     */
    public function testWrittenInterfaceContainsAllExtendedInterfaceNames()
    {
        $this->interfaceWriter->setExtendingInterfaces('Foo, Bar');
        $this->interfaceWriter->writeToFile();
        $this->assertSameContentAtLine('interface TestInterface extends Foo, Bar', 1);
    }

    /**
     * @covers InterfaceWriter::addMethod
     * @covers InterfaceWriter::writeToFile
     * @covers InterfaceWriter::__construct
     * @covers InterfaceWriter::<!public>
     * @dataProvider provideReflectionMethodsAndExpectedSignatures
     * @param ReflectionMethod $method
     * @param string $expectedSignature
     */
    public function testWrittenInterfaceContainsAddedMethods(ReflectionMethod $method, $expectedSignature)
    {
        $this->interfaceWriter->addMethod($method);
        $this->interfaceWriter->writeToFile();
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
        	'public function fn3(TestClass $testClass);',
        	'public function fn4($bar = 1);',
        	'public function fn5(&$bar);',
        	'public function fn6(array $foo);',
        	'public function fn7(array $foo = array());',
        	'public function fn8($foo = array());'
        );
        foreach ($signatures as $i => $signature) {
            $data[] = array(
                new ReflectionMethod('TestClass', 'fn' . ($i + 1)),
                $signature
            );
        }
        return $data;
    }

    /**
     * @covers InterfaceWriter::addMethod
     * @covers InterfaceWriter::writeToFile
     * @covers InterfaceWriter::__construct
     * @covers InterfaceWriter::<!public>
     */
    public function testWrittenMethodsContainExistingDocBlocks()
    {
        $this->interfaceWriter->addMethod(
            new ReflectionMethod('TestClass', 'foo')
        );
        $this->interfaceWriter->writeToFile();
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