<?php
namespace com\github\gooh\InterfaceDistiller\Controller;
class CommandLine
{
    /**
     * @var \com\github\gooh\InterfaceDistiller\InterfaceDistiller
     */
    protected $interfaceDistiller;

    /**
     * @param \com\github\gooh\InterfaceDistiller\InterfaceDistiller $distiller
     * @return void
     */
    public function __construct(\com\github\gooh\InterfaceDistiller\InterfaceDistiller $interfaceDistiller)
    {
        $this->interfaceDistiller = $interfaceDistiller;
    }

    /**
     * @param array $cliArguments
     * @param resource $outputStream
     * @return void
     */
    public function __invoke(array $cliArguments, $outputStream)
    {
        $this->handleInput($cliArguments, $outputStream);
    }

    /**
     * @param array $cliArguments
     * @param \SplFileObject $outputStream
     * @return void
     */
    public function handleInput(array $cliArguments, \SplFileObject $outputStream)
    {
        $this->interfaceDistiller->saveAs($outputStream);
        $unappliedOptions = $this->applyOptions($cliArguments);
        if (count($unappliedOptions) === 2) {
            $this->interfaceDistiller->distill(
                $unappliedOptions[0],
                $unappliedOptions[1]
            );
            $outputStream->fwrite(PHP_EOL . 'Done.' . PHP_EOL);
        } else {
            $outputStream->fwrite($this->getUsage() . PHP_EOL);
        }
    }

    /**
     * @param array $cliArguments
     * @return array
     */
    protected function applyOptions(array $cliArguments)
    {
        $options = array();
        array_shift($cliArguments);
        while (($arg = array_shift($cliArguments)) !== null) {
            $arg = $this->removePrefixDashes($arg);
            switch ($arg) {
                case 'filterMethodsByPattern':
                case 'methodsWithModifiers':
                case 'extendInterfaceFrom':
                    $this->interfaceDistiller->$arg(array_shift($cliArguments));
                	break;
                case 'saveAs':
                    $this->interfaceDistiller->$arg(
                        new \SplFileObject(array_shift($cliArguments), 'w')
                    );
                    break;
                case 'excludeImplementedMethods':
                case 'excludeInheritedMethods':
                case 'excludeMagicMethods':
                case 'excludeOldStyleConstructors':
                    $this->interfaceDistiller->$arg();
                	break;
                case 'bootstrap':
                    $bootstrap = array_shift($cliArguments);
                    require $bootstrap;
                    break;
                default:
                    $options[] = $arg;
            }
        }
        return $options;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function removePrefixDashes($string)
    {
        return ltrim($string, '-');
    }

    /**
     * @return string
     */
    protected function getUsage()
    {
        return <<< TXT
Interface Distiller 1.0.3

Usage: phpdistill [options] <classname> <interfacename>

  --bootstrap                           Path to File containing your bootstrap and autoloader

  --methodsWithModifiers <number>       A ReflectionMethod Visibility BitMask. Defaults to Public.
  --extendInterfaceFrom  <name,...>     Comma-separated list of Interfaces to extend.
  --excludeImplementedMethods           Will exclude all implemented methods.
  --excludeInheritedMethods             Will exclude all inherited methods.
  --excludeMagicMethods                 Will exclude all magic methods.
  --excludeOldStyleConstructors         Will exclude Legacy Constructors.
  --filterMethodsByPattern <pattern>    Only include methods matching PCRE pattern.
  --saveAs                              Filename to save new Interface to. STDOUT if omitted.
TXT;
    }
}