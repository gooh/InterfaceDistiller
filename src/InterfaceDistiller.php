<?php
namespace com\github\gooh\InterfaceDistiller;
class InterfaceDistiller
{
    /**
     * @var \com\github\gooh\InterfaceDistiller\Distillate
     */
    protected $distillate;

    /**
     * @var string
     */
    protected $reflectionClass;

    /**
     * @var integer
     */
    protected $methodModifiers;

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
        $this->reset();
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->distillate = new Distillate();
        $this->excludeImplementedMethods = false;
        $this->excludeInheritedMethods = false;
        $this->excludeMagicMethods = false;
        $this->excludeOldStyleConstructors = false;
        $this->excludeTraitMethods = false;
        $this->methodModifiers = \ReflectionMethod::IS_PUBLIC;
        $this->pcrePattern = false;
        $this->reflectionClass = null;
        $this->saveAs = null;
    }

    /**
     * @param  integer $reflectionMethodModifiersMask
     * @return \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    public function methodsWithModifiers($reflectionMethodModifiersMask)
    {
        $this->methodModifiers = $reflectionMethodModifiersMask;
        return $this;
    }

    /**
     * @param  string $commaSeparatedInterfaceNames
     * @return \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    public function extendInterfaceFrom($commaSeparatedInterfaceNames)
    {
        $this->distillate->setExtendingInterfaces($commaSeparatedInterfaceNames);
        return $this;
    }

    /**
     * @return \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    public function excludeImplementedMethods()
    {
        $this->excludeImplementedMethods = true;
        return $this;
    }

    /**
     * @return \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    public function excludeInheritedMethods()
    {
        $this->excludeInheritedMethods = true;
        return $this;
    }

    /**
     * @return \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    public function excludeTraitMethods()
    {
        $this->excludeTraitMethods = true;
        return $this;
    }

    /**
     * @return \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    public function excludeMagicMethods()
    {
        $this->excludeMagicMethods = true;
        return $this;
    }

    /**
     * @return \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    public function excludeOldStyleConstructors()
    {
        $this->excludeOldStyleConstructors = true;
        return $this;
    }

    /**
     * @param string $pcrePattern
     * @return \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    public function filterMethodsByPattern($pcrePattern)
    {
        $this->pcrePattern = $pcrePattern;
        return $this;
    }

    /**
     * @param \SplFileObject $fileObject
     * @return \com\github\gooh\InterfaceDistiller\InterfaceDistiller
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
        $this->reflectionClass = $fromClassName;
        $this->distillate->setInterfaceName($intoInterfaceName);
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
