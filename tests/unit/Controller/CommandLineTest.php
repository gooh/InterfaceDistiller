<?php
namespace com\github\gooh\InterfaceDistiller\Controller;
/**
 * @covers \com\github\gooh\InterfaceDistiller\Controller\CommandLine::<!public>
 * @covers \com\github\gooh\InterfaceDistiller\Controller\CommandLine::__construct
 * @covers \com\github\gooh\InterfaceDistiller\Controller\CommandLine::__invoke
 */
class CommandLineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \com\github\gooh\InterfaceDistiller\Controller\CommandLine
     */
    protected $commandLineController;

    /**
     * @var \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    protected $distillerMock;

    /**
     * @return void
     */
    public function setup()
    {
        $baseNamespace = '\\com\\github\\gooh\\InterfaceDistiller\\';
        $this->distillerMock = $this->getMock($baseNamespace . 'InterfaceDistiller');
        $this->commandLineController = new CommandLine($this->distillerMock);
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Controller\CommandLine::handleInput
     */
    public function testControllerConfiguresInterfaceDistillerWithCommandLineOptions()
    {
        $outStream = new \SplTempFileObject(-1);

        $cliArguments = array(
            'scriptname',
			'--filterMethodsByPattern', '^get',
            '--methodsWithModifiers', 256,
        	'--extendInterfaceFrom', 'foo',
        	'--saveAs', 'php://memory',
        	'--excludeImplementedMethods',
        	'--excludeInheritedMethods',
        	'--excludeMagicMethods',
        	'--excludeOldStyleConstructors',
            'SomeClass',
            'SomeInterface'
        );

        $this->distillerMock
            ->expects($this->once())
            ->method('filterMethodsByPattern')
            ->with('^get');

        $this->distillerMock
            ->expects($this->once())
            ->method('methodsWithModifiers')
            ->with('256');

        $this->distillerMock
            ->expects($this->once())
            ->method('extendInterfaceFrom')
            ->with('foo');

        $this->distillerMock
            ->expects($this->exactly(2))
            ->method('saveAs')
            ->with($this->logicalOr($outStream, new \SplFileObject('php://memory')));

        $this->distillerMock
            ->expects($this->once())
            ->method('excludeImplementedMethods');

        $this->distillerMock
            ->expects($this->once())
            ->method('excludeInheritedMethods');

        $this->distillerMock
            ->expects($this->once())
            ->method('excludeMagicMethods');

        $this->distillerMock
            ->expects($this->once())
            ->method('excludeOldStyleConstructors');

        $this->distillerMock
            ->expects($this->once())
            ->method('distill')
            ->with('SomeClass', 'SomeInterface');

        call_user_func($this->commandLineController, $cliArguments, $outStream);
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Controller\CommandLine::handleInput
     */
    public function testControllerWritesInterfaceToOutputStreamWhenSaveAsOptionIsNotGiven()
    {
        $outStream = new \SplTempFileObject(-1);

        $this->distillerMock
            ->expects($this->once())
            ->method('saveAs')
            ->with($outStream);

        call_user_func(
            $this->commandLineController,
            array('scriptname', 'SomeClass', 'SomeInterface'),
            $outStream
        );
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Controller\CommandLine::handleInput
     */
    public function testControllerOutputsUsageInformationWhenRequiredArgumentsAreMissing()
    {
        $outStream = new \SplTempFileObject(-1);
        $reflector = new \ReflectionMethod($this->commandLineController, 'getUsage');
        $reflector->setAccessible(true);
        call_user_func($this->commandLineController, array(), $outStream);
        $this->assertSame(
            $reflector->invoke($this->commandLineController),
            trim(implode('', iterator_to_array($outStream)))
        );
    }
}