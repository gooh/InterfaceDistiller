<?php
namespace com\github\gooh\InterfaceDistiller;

/**
 * @covers \com\github\gooh\InterfaceDistiller\Distillate::<!public>
 * @covers \com\github\gooh\InterfaceDistiller\Distillate::__construct
 */
class DistillateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \com\github\gooh\InterfaceDistiller\Distillate
     */
    private $distillate;

    /**
     * @return void
     */
    public function setup()
    {
        $this->distillate = new \com\github\gooh\InterfaceDistiller\Distillate;
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate::getInterfaceName
     */
    public function testNewDistillateHasDefaultInterfaceName()
    {
        $this->assertSame('MyInterface', $this->distillate->getInterfaceName());
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate::getExtendingInterfaces
     */
    public function testNewDistillateDoesNotExtendAnyInterfaces()
    {
        $this->assertSame('', $this->distillate->getExtendingInterfaces());
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate::getInterfaceMethods
     */
    public function testNewDistillateDoesNotHaveAnyInterfaceMethods()
    {
        $this->assertEquals(array(), $this->distillate->getInterfaceMethods());
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate::getInterfaceName
     * @covers \com\github\gooh\InterfaceDistiller\Distillate::setInterfaceName
     */
    public function testCanGetAndSetInterfaceName()
    {
        $this->distillate->setInterfaceName('NewInterface');
        $this->assertSame('NewInterface', $this->distillate->getInterfaceName());
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate::getExtendingInterfaces
     * @covers \com\github\gooh\InterfaceDistiller\Distillate::setExtendingInterfaces
     */
    public function testCanGetAndSetExtendingInterfaces()
    {
        $this->distillate->setExtendingInterfaces('Countable');
        $this->assertSame('Countable', $this->distillate->getExtendingInterfaces());
    }

    /**
     * @covers \com\github\gooh\InterfaceDistiller\Distillate::addMethod
     * @covers \com\github\gooh\InterfaceDistiller\Distillate::getInterfaceMethods
     */
    public function testCanGetAndSetInterfaceMethods()
    {
        $method = $this->getMock('\ReflectionMethod', array(), array(), '', false);
        $this->distillate->addMethod($method);
        $this->assertEquals(
            array($method),
            $this->distillate->getInterfaceMethods()
        );
    }
}