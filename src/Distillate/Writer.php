<?php
namespace com\github\gooh\InterfaceDistiller\Distillate;
class Writer
{
    /**
     * @var \SplFileObject
     */
    protected $fileObject;

    /**
     * @param \SplFileObject $fileObject
     * @return void
     */
    public function __construct(\SplFileObject $fileObject)
    {
        $this->fileObject = $fileObject;
    }

    /**
     * @param \com\github\gooh\InterfaceDistiller\Accessors $distillate
     * @return void
     */
    public function writeToFile(Accessors $distillate)
    {
        $this->writeString('<?php' . PHP_EOL);
        $this->writeInterfaceSignature(
            $distillate->getInterfaceName(),
            $distillate->getExtendingInterfaces()
        );
        $this->writeString('{' . PHP_EOL);
        $this->writeMethods($distillate->getInterfaceMethods());
        $this->writeString('}');
    }

    /**
     * @param string $string
     * @return void
     */
    protected function writeString($string)
    {
        $this->fileObject->fwrite($string);
    }

    /**
     * @return void
     */
    protected function writeInterfaceSignature($interfaceName, $extendingInterfaces = false)
    {
        $this->writeString("interface $interfaceName");
        if ($extendingInterfaces) {
            $this->writeString(" extends $extendingInterfaces");
        }
        $this->writeString(PHP_EOL);
    }

    /**
     * @return void
     */
    protected function writeMethods(array $methods)
    {
        foreach ($methods as $method) {
            $this->writeMethod($method);
            $this->writeString(PHP_EOL);
        }
    }

    /**
     * @param \ReflectionMethod $method
     * @return void
     */
    protected function writeMethod(\ReflectionMethod $method)
    {
        $this->writeString(
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
            $this->writeString('    ');
            $this->writeString($method->getDocComment());
            $this->writeString(PHP_EOL);
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
        return $reflectionParameter->getClass()->getName();
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
        if ($parameter->getDeclaringClass()->isInternal()) {
            // Last try to get some valuable data for default-value of internal classes ...
            if ($parameter->allowsNull())
                return ' = NULL ';
            else
                return ' /* internal default */ ';
        }
        throw new \RuntimeException(
            sprintf(
            	'Optional Parameter %s has no default value',
                $parameter->getName()
            )
        );
    }
}