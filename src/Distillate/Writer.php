<?php
namespace com\github\gooh\InterfaceDistiller\Distillate;
class Writer
{
    /**
     * @var \SplFileObject
     */
    protected $fileObject;

    /**
     * @param string $interfaceName
     * @return void
     */
    public function __construct(\SplFileObject $fileObject)
    {
        $this->fileObject = $fileObject;
    }

    /**
     * @param Accessors $interface
     * @return void
     */
    public function writeToFile(Accessors $interface)
    {
        $this->fileObject->fwrite('<?php' . PHP_EOL);
        $this->writeInterfaceSignature(
            $interface->getInterfaceName(),
            $interface->getExtendingInterfaces()
        );
        $this->fileObject->fwrite('{' . PHP_EOL);
        $this->writeMethods($interface->getInterfaceMethods());
        $this->fileObject->fwrite('}');
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
    protected function writeMethods(array $methods)
    {
        foreach ($methods as $method) {
            $this->writeMethod($method);
            $this->fileObject->fwrite(PHP_EOL);
        }
    }

    /**
     * @param \ReflectionMethod $method
     * @return void
     */
    protected function writeMethod(\ReflectionMethod $method)
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
     * @param \ReflectionMethod $method
     * @return void
     */
    protected function writeDocCommentOfMethod(\ReflectionMethod $method)
    {
        if ($method->getDocComment()) {
            $this->fileObject->fwrite($method->getDocComment());
            $this->fileObject->fwrite(PHP_EOL);
        }
    }

    /**
     * @param \ReflectionMethod $method
     * @return string
     */
    protected function methodParametersToString(\ReflectionMethod $method)
    {
        return implode(
        	', ',
            array_map(
                array($this, 'parameterToString'),
                $method->getParameters()
            )
        );
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     */
    protected function parameterToString(\ReflectionParameter $parameter)
    {
        return trim(
            sprintf(
            	'%s%s %s$%s%s',
                $parameter->getClass() ? $this->resolveTypeHint($parameter) : '',
                $parameter->isArray() ? 'array' : '',
                $parameter->isPassedByReference() ? '&' : '',
                $parameter->name,
                $this->resolveDefaultValue($parameter)
            )
        );
    }

    /**
     * @param \ReflectionParameter $reflectionParameter
     * @return string
     */
    protected function resolveTypeHint(\ReflectionParameter $reflectionParameter)
    {
        if ($reflectionParameter->getDeclaringClass()->inNamespace()) {
            $typeHint = $reflectionParameter->getClass();
            return $typeHint->isInternal()
                ? '\\' . $typeHint->getName()
                : $typeHint->getName();
        }
        return $reflectionParameter->getClass();
    }

    /**
     * @throws \RuntimeException When $parameter is optional without a default value
     * @param \ReflectionParameter $parameter
     * @return string
     */
    protected function resolveDefaultValue(\ReflectionParameter $parameter)
    {
        if (!$parameter->isOptional()) {
            return;
        }
        if ($parameter->isDefaultValueAvailable()) {
            return ' = ' . preg_replace(
            	'(\s)',
            	'',
                var_export($parameter->getDefaultValue(), true)
            );
        }
        throw new \RuntimeException(
            sprintf(
            	'Optional Parameter %s has no default value',
                $parameter->getName()
            )
        );
    }
}