<?php
class InterfaceWriter
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
    protected $methods;

    /**
     * @var SplFileObject
     */
    protected $fileObject;

    /**
     * @param string $interfaceName
     * @return void
     */
    public function __construct($interfaceName, SplFileObject $fileObject)
    {
        $this->interfaceName = $interfaceName;
        $this->fileObject = $fileObject;
        $this->methods = new SplObjectStorage;
    }

    /**
     * @param string $commaSeparatedInterfaceNames
     * @return void
     */
    public function setExtendingInterfaces($commaSeparatedInterfaceNames)
    {
        $this->extendingInterfaces = $commaSeparatedInterfaceNames;
    }

    /**
     * @param ReflectionMethod $reflectionMethod
     * @return void
     */
    public function addMethod(ReflectionMethod $reflectionMethod)
    {
        $this->methods->attach($reflectionMethod);
    }

    /**
     * @param array $reflectionMethods
     * @return void
     */
    public function addMethods(array $reflectionMethods)
    {
        foreach ($reflectionMethods as $reflectionMethod) {
            $this->addMethod($reflectionMethod);
        }
    }

    /**
     * @return void
     */
    public function writeToFile()
    {
        $this->writePhpOpeningTag();
        $this->writeInterfaceSignature();
        $this->writeOpeningBrace();
        $this->writeMethods();
        $this->writeClosingBrace();
    }

    /**
     * @return void
     */
    protected function writePhpOpeningTag()
    {
        $this->fileObject->fwrite('<?php' . PHP_EOL);
    }

    /**
     * @return void
     */
    protected function writeInterfaceSignature()
    {
        $this->fileObject->fwrite("interface {$this->interfaceName}");
        if ($this->extendingInterfaces) {
            $this->fileObject->fwrite(" extends {$this->extendingInterfaces}");
        }
        $this->fileObject->fwrite(PHP_EOL);
    }

    /**
     * @return void
     */
    protected function writeOpeningBrace()
    {
		$this->fileObject->fwrite('{' . PHP_EOL);
    }

    /**
     * @return void
     */
    protected function writeMethods()
    {
        foreach ($this->methods as $method) {
            $this->fileObject->fwrite($this->writeMethod($method));
            $this->fileObject->fwrite(PHP_EOL);
        }
    }

    /**
     * @param ReflectionMethod $method
     * @return void
     */
    protected function writeMethod(ReflectionMethod $method)
    {
        $this->fileObject->fwrite(
            sprintf(
            	'%s    public%sfunction %s(%s);',
                $this->writeDocCommentOfMethod($method),
                $method->isStatic() ? ' static ' : ' ',
                $method->name,
                $this->methodParametersToString($method)
            )
        );
    }

    /**
     * @param ReflectionMethod $method
     * @return void
     */
    protected function writeDocCommentOfMethod(ReflectionMethod $method)
    {
        if ($method->getDocComment()) {
            $this->fileObject->fwrite($method->getDocComment());
            $this->fileObject->fwrite(PHP_EOL);
        }
    }

    /**
     * @param ReflectionMethod $method
     * @return string
     */
    protected function methodParametersToString(ReflectionMethod $method)
    {
        return implode(', ', array_map(
            array($this, 'parameterToString'),
            $method->getParameters()
        ));
    }

    /**
     * @param ReflectionParameter $parameter
     * @return string
     */
    protected function parameterToString(ReflectionParameter $parameter)
    {
        return trim(
            sprintf(
            	'%s%s %s$%s%s',
                $parameter->getClass() ? $parameter->getClass()->name : '',
                $parameter->isArray() ? 'array' : '',
                $parameter->isPassedByReference() ? '&' : '',
                $parameter->name,
                $parameter->isOptional()
                    ? ($parameter->isArray()
                        ? ' = array()'
                        : ($parameter->isDefaultValueAvailable()
                            ? (is_array($parameter->getDefaultValue())
                                ? ' = array()'
                                : ' = ' . $parameter->getDefaultValue()
                            )
                            : ' = null'
                        )
                    ) : ''
            )
        );
    }

    /**
     * @return void
     */
    protected function writeClosingBrace()
    {
        $this->fileObject->fwrite('}');
    }
}