<?php
class Distillate implements Accessors, Mutators
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
     * @var SplObjectStorage
     */
    protected $interfaceMethods;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->interfaceName = 'MyInterface';
        $this->extendingInterfaces = '';
        $this->interfaceMethods = new SplObjectStorage;
    }

    /**
     * @see InterfaceMutators::setInterfaceName()
     */
    public function setInterfaceName($interfaceName)
    {
        $this->interfaceName = $interfaceName;
    }

    /**
     * @see InterfaceMutators::setExtendingInterfaces()
     */
    public function setExtendingInterfaces($commaSeparatedInterfaceNames)
    {
        $this->extendingInterfaces = $commaSeparatedInterfaceNames;
    }

    /**
     * @see InterfaceMutators::addMethod()
     */
    public function addMethod(ReflectionMethod $reflectionMethod)
    {
        $this->interfaceMethods->attach($reflectionMethod);
    }

    /**
     * @see InterfaceAccessors::getInterfaceName()
     */
    public function getInterfaceName()
    {
        return $this->interfaceName;
    }

    /**
     * @see InterfaceAccessors::getInterfaceMethods()
     */
    public function getInterfaceMethods()
    {
        return iterator_to_array($this->interfaceMethods);
    }

    /**
     * @see InterfaceAccessors::getExtendingInterfaces()
     */
    public function getExtendingInterfaces()
    {
        return $this->extendingInterfaces;
    }
}