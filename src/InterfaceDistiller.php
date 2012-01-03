<?php
namespace com\github\gooh\InterfaceDistiller;
class InterfaceDistiller
{
    /**
     * @var Distillate
     */
    protected $distillate;

    /**
     * @var string
     */
    protected $reflectionClass;

    /**
     * @var integer
     */
    protected $methodModifiers = \ReflectionMethod::IS_PUBLIC;

    /**
     * @var boolean
     */
    protected $excludeImplementedMethods;

    /**
     * @var boolean
     */
    protected $excludeInheritedMethods;

    /**
     * @var boolean
     */
    protected $excludeTraitMethods;

    /**
     * @var boolean
     */
    protected $excludeMagicMethods;

    /**
     * @var boolean
     */
    protected $excludeOldStyleConstructors;

    /**
     * @var string
     */
    protected $pcrePattern;

    /**
     * @var \SplFileObject
     */
    protected $saveAs;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->distillate = new Distillate();
    }

    /**
     * @param  string $className
     * @return void
     */
    protected function distillFromClass($className)
    {
        $this->reflectionClass = $className;
    }

    /**
     * @param  string $interfaceName
     * @return void
     */
    protected function distillIntoInterface($interfaceName)
    {
        $this->distillate->setInterfaceName($interfaceName);
    }

    /**
     * @param  integer $reflectionMethodModifiersMask
     * @return InterfaceDistiller
     */
    public function methodsWithModifiers($reflectionMethodModifiersMask)
    {
        $this->methodModifiers = $reflectionMethodModifiersMask;
        return $this;
    }

    /**
     * @param  string $commaSeparatedInterfaceNames
     * @return InterfaceDistiller
     */
    public function extendInterfaceFrom($commaSeparatedInterfaceNames)
    {
        $this->distillate->setExtendingInterfaces($commaSeparatedInterfaceNames);
        return $this;
    }

    /**
     * @return InterfaceDistiller
     */
    public function excludeImplementedMethods()
    {
        $this->excludeImplementedMethods = true;
        return $this;
    }

    /**
     * @return InterfaceDistiller
     */
    public function excludeInheritedMethods()
    {
        $this->excludeInheritedMethods = true;
        return $this;
    }

    /**
     * @return InterfaceDistiller
     */
    public function excludeTraitMethods()
    {
        $this->excludeTraitMethods = true;
        return $this;
    }

    /**
     * @return InterfaceDistiller
     */
    public function excludeMagicMethods()
    {
        $this->excludeMagicMethods = true;
        return $this;
    }

    /**
     * @return InterfaceDistiller
     */
    public function excludeOldStyleConstructors()
    {
        $this->excludeOldStyleConstructors = true;
        return $this;
    }

    /**
     * @param string $pcrePattern
     * @return InterfaceDistiller
     */
    public function filterMethodsByPattern($pcrePattern)
    {
        $this->pcrePattern = $pcrePattern;
        return $this;
    }

    /**
     * @param \SplFileObject $fileObject
     * @return InterfaceDistiller
     */
    public function saveAs(\SplFileObject $fileObject)
    {
        $this->saveAs = $fileObject;
        return $this;
    }

    /**
     * @param string $fromClassName
     * @param string $intoInterfaceName
     * @return void
     */
    public function distill($fromClassName, $intoInterfaceName)
    {
        $this->distillFromClass($fromClassName);
        $this->distillIntoInterface($intoInterfaceName);
        $this->prepareDistillate();
        $this->writeDistillate();
    }

    /**
     * @return void
     */
    protected function prepareDistillate()
    {
        $reflector = new \ReflectionClass($this->reflectionClass);
        $iterator = new \ArrayIterator(
            $reflector->getMethods($this->methodModifiers)
        );
        foreach ($this->decorateMethodIterator($iterator, $reflector) as $method) {
            $this->distillate->addMethod($method);
        }
    }

	/**
     * @param \ArrayIterator $iterator
     * @param \ReflectionClass $reflector
     * @return \Iterator
     */
    protected function decorateMethodIterator(\ArrayIterator $iterator, \ReflectionClass $reflector)
    {
        if ($this->pcrePattern) {
            $iterator = new Filters\RegexMethodIterator($iterator, $this->pcrePattern);
        }
        if ($this->excludeImplementedMethods) {
            $iterator = new Filters\NoImplementedMethodsIterator($iterator);
        }
        if ($this->excludeInheritedMethods) {
            $iterator = new Filters\NoInheritedMethodsIterator($iterator, $reflector);
        }
        if ($this->excludeOldStyleConstructors) {
            $iterator = new Filters\NoOldStyleConstructorIterator($iterator);
        }
        if ($this->excludeMagicMethods) {
            $iterator = new Filters\NoMagicMethodsIterator($iterator);
        }
        if ($this->excludeTraitMethods) {
            $iterator = new Filters\NoTraitMethodsIterator($iterator);
        }
        return $iterator;
    }

    /**
     * @return void
     */
    protected function writeDistillate()
    {
        $writer = new Distillate\Writer($this->saveAs);
        $writer->writeToFile($this->distillate);
    }
}
