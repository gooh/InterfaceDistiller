<?php
class Writer
{
    /**
     * @var SplFileObject
     */
    protected $fileObject;

    /**
     * @param string $interfaceName
     * @return void
     */
    public function __construct(SplFileObject $fileObject)
    {
        $this->fileObject = $fileObject;
    }

    /**
     * @param Accessors $interface
     * @return void
     */
    public function writeToFile(Accessors $interface)
    {
        $this->writePhpOpeningTag();
        $this->writeInterfaceSignature(
            $interface->getInterfaceName(),
            $interface->getExtendingInterfaces()
        );
        $this->writeOpeningBrace();
        $this->writeMethods($interface->getInterfaceMethods());
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
    protected function writeInterfaceSignature($interfaceName, $extendingInterfaces = false)
    {
        $this->fileObject->fwrite("interface {$interfaceName}");
        if ($extendingInterfaces) {
            $this->fileObject->fwrite(" extends {$extendingInterfaces}");
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
    protected function writeMethods(array $methods)
    {
        foreach ($methods as $method) {
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