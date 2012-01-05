<?php
namespace com\github\gooh\InterfaceDistiller;
class Distillate implements Distillate\Accessors, Distillate\Mutators
{
    /**
     * @var string
     */
    protected $interfaceName;

    /**
     * @var string
     */
    protected $extendingInterfaces;

    /**
     * @var \SplObjectStorage
     */
    protected $interfaceMethods;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->interfaceName = 'MyInterface';
        $this->extendingInterfaces = '';
        $this->interfaceMethods = new \SplObjectStorage;
    }

    /**
     * @see \com\github\gooh\InterfaceDistiller\Distillate\Mutators::setInterfaceName()
     */
    public function setInterfaceName($interfaceName)
    {
        $this->interfaceName = $interfaceName;
    }

    /**
     * @see \com\github\gooh\InterfaceDistiller\Distillate\Mutators::setExtendingInterfaces()
     */
    public function setExtendingInterfaces($commaSeparatedInterfaceNames)
    {
        $this->extendingInterfaces = $commaSeparatedInterfaceNames;
    }

    /**
     * @see \com\github\gooh\InterfaceDistiller\Distillate\Mutators::addMethod()
     */
    public function addMethod(\ReflectionMethod $reflectionMethod)
    {
        $this->interfaceMethods->attach($reflectionMethod);
    }

    /**
     * @see \com\github\gooh\InterfaceDistiller\Distillate\Accessors::getInterfaceName()
     */
    public function getInterfaceName()
    {
        return $this->interfaceName;
    }

    /**
     * @see \com\github\gooh\InterfaceDistiller\Distillate\Accessors::getInterfaceMethods()
     */
    public function getInterfaceMethods()
    {
        return iterator_to_array($this->interfaceMethods);
    }

    /**
     * @see \com\github\gooh\InterfaceDistiller\Distillate\Accessors::getExtendingInterfaces()
     */
    public function getExtendingInterfaces()
    {
        return $this->extendingInterfaces;
    }
}