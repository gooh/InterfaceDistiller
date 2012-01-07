<?php
namespace com\github\gooh\InterfaceDistiller;
require __DIR__ . '/_files/DistillTestClass.php';

/**
 * @covers \com\github\gooh\InterfaceDistiller\InterfaceDistiller
 */
class InterfaceDistillerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    private $interfaceDistiller;

    /**
     * @var \SplTempFileObject
     */
    private $fileObject;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->interfaceDistiller = new InterfaceDistiller;
        $this->fileObject = new \SplTempFileObject(-1);
        $this->interfaceDistiller->saveAs($this->fileObject);
    }

    /**
     * @return void
     */
    public function testDistillWithNoOptionsSet()
    {
        $this->interfaceDistiller->distill(
            '\\com\\github\\gooh\\InterfaceDistiller\\DistillTestClass',
            'DistillWithNoOptionsSetInterface'
        );
        $this->assertWrittenInterfaceEqualsExpectedFile('distillWithNoOptionsSetInterface.php');
    }

    /**
     * @return void
     */
    public function testDistillUsingAllExcludeOptions()
    {
        $this->interfaceDistiller
            ->excludeImplementedMethods()
            ->excludeInheritedMethods()
            ->excludeMagicMethods()
            ->excludeOldStyleConstructors()
            ->distill(
                '\\com\\github\\gooh\\InterfaceDistiller\\DistillTestClass',
                'DistillWithAllExcludeOptionsSetInterface'
        );
        $this->assertWrittenInterfaceEqualsExpectedFile('distillWithAllExcludeOptionsSetInterface.php');
    }

    /**
     * @return void
     */
    public function testDistillUsingFilter()
    {
        $this->interfaceDistiller
            ->filterMethodsByPattern('(^public.+WithParameters$)')
            ->distill(
                '\\com\\github\\gooh\\InterfaceDistiller\\DistillTestClass',
                'DistillWithFilterInterface'
        );
        $this->assertWrittenInterfaceEqualsExpectedFile('distillWithFilterInterface.php');
    }

    /**
     * @return void
     */
    public function testDistillUsingExtendingInterfacesOption()
    {
        $this->interfaceDistiller
            ->extendInterfaceFrom('Foo, Bar')
            ->distill(
                '\\com\\github\\gooh\\InterfaceDistiller\\DistillTestClass',
                'DistillWithExtendingInterfacesOptionSetInterface'
        );
        $this->assertWrittenInterfaceEqualsExpectedFile(
        	'distillWithExtendingInterfacesOptionsSetInterface.php'
        );
    }

    /**
     * @requires PHP 5.4
     */
    public function testDistillUsingExcludeTraitMethodOption()
    {
        $this->interfaceDistiller
            ->extendInterfaceFrom('Foo, Bar')
            ->distill(
                '\\com\\github\\gooh\\InterfaceDistiller\\DistillTestClassWithTrait',
                'DistillWithTraitsExcludedOptionSetInterface'
        );
        $this->assertWrittenInterfaceEqualsExpectedFile(
        	'distillWithTraitsExcludedOptionSetInterface.php'
        );
    }

    /**
     * @param string $expectedFileName
     * @return void
     */
    private function assertWrittenInterfaceEqualsExpectedFile($expectedFileName)
    {
        $expectedContent = $this->replaceNewLinesToSystemSpecificNewLines(
            trim(file_get_contents(__DIR__ . '/_files/' . $expectedFileName))
        );
        $actualContent = $this->replaceNewLinesToSystemSpecificNewLines(
            trim(implode('', iterator_to_array($this->fileObject)))
        );
        $this->assertSame($expectedContent, $actualContent);
    }

    /**
     * @param string $string
     * @return string
     */
    private function replaceNewLinesToSystemSpecificNewLines($string)
    {
        return preg_replace('~(*BSR_ANYCRLF)\R~', PHP_EOL, $string);
    }
}