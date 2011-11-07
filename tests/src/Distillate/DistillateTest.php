<?php
namespace com\github\gooh\InterfaceDistiller\Tests;
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
     * @covers Distillate::__construct
     * @covers Distillate::getInterfaceName
     */
    public function testNewDistillateHasDefaultInterfaceName()
    {
        $this->assertSame('MyInterface', $this->distillate->getInterfaceName());
    }

    /**
     * @covers Distillate::__construct
     * @covers Distillate::getExtendingInterfaces
     */
    public function testNewDistillateDoesNotExtendAnyInterfaces()
    {
        $this->assertSame('', $this->distillate->getExtendingInterfaces());
    }

    /**
     * @covers Distillate::__construct
     * @covers Distillate::getInterfaceMethods
     */
    public function testNewDistillateDoesNotHaveAnyInterfaceMethods()
    {
        $this->assertEquals(array(), $this->distillate->getInterfaceMethods());
    }

    /**
     * @covers Distillate::getInterfaceName
     * @covers Distillate::setInterfaceName
     */
    public function testCanGetAndSetInterfaceName()
    {
        $this->distillate->setInterfaceName('NewInterface');
        $this->assertSame('NewInterface', $this->distillate->getInterfaceName());
    }

    /**
     * @covers Distillate::getExtendingInterfaces
     * @covers Distillate::setExtendingInterfaces
     */
    public function testCanGetAndSetExtendingInterfaces()
    {
        $this->distillate->setExtendingInterfaces('Countable');
        $this->assertSame('Countable', $this->distillate->getExtendingInterfaces());
    }

    /**
     * @covers Distillate::getInterfaceMethods
     * @covers Distillate::setInterfaceMethods
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