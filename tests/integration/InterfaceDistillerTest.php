<?php
namespace com\github\gooh\InterfaceDistiller;
require __DIR__ . '/_files/DistillTestClass.php';

/**
 * @covers \com\github\gooh\InterfaceDistiller\InterfaceDistiller
 */
class InterfaceDistillerTest extends \PHPUnit_Framework_TestCase
{
    private $interfaceDistillate;
    private $fileObject;

    public function setUp()
    {
        $this->interfaceDistillate = new InterfaceDistiller();
        $this->fileObject = new \SplTempFileObject();
        $this->interfaceDistillate->saveAs($this->fileObject);
    }

    public function testDistillWithNoOptionsSet()
    {
        $this->interfaceDistillate->distill(
            '\\com\\github\\gooh\\InterfaceDistiller\\DistillTestClass', 
            'DistillWithNoOptionsSetInterface'
        );
        $this->assertDistillateEqualsExpectedFile('distillWithNoOptionsSetInterface.php');
    }

    public function testDistillUsingAllExcludeOptions()
    {
        $this->interfaceDistillate->excludeImplementedMethods();
        $this->interfaceDistillate->excludeInheritedMethods();
        $this->interfaceDistillate->excludeMagicMethods();
        $this->interfaceDistillate->excludeOldStyleConstructors();
        $this->interfaceDistillate->distill(
            '\\com\\github\\gooh\\InterfaceDistiller\\DistillTestClass', 
            'DistillWithAllExcludeOptionsSetInterface'
        );
        $this->assertDistillateEqualsExpectedFile('distillWithAllExcludeOptionsSetInterface.php');
    }

    public function testDistillUsingFilter()
    {
        $this->interfaceDistillate->filterMethodsByPattern('(^public.+WithParameters$)');
        $this->interfaceDistillate->distill(
            '\\com\\github\\gooh\\InterfaceDistiller\\DistillTestClass',
            'DistillWithFilterInterface'
        );
        $this->assertDistillateEqualsExpectedFile('distillWithFilterInterface.php');
    }

    private function assertDistillateEqualsExpectedFile($expectedFile)
    {
        $this->fileObject->fseek(0);
        ob_start();
        $this->fileObject->fpassthru();
        $actual = ob_get_contents();
        ob_end_clean();
        $this->assertSame(
            trim(file_get_contents(__DIR__ . '/_files/' . $expectedFile)),            
            $actual
        );
    }
}

