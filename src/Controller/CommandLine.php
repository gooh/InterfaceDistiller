<?php
namespace com\github\gooh\InterfaceDistiller\Controller;
class CommandLine
{
    /**
     * @var InterfaceDistiller
     */
    protected $interfaceDistiller;

    /**
     * @var boolean
     */
    protected $printInterfaceToStream = true;

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
     * @param resource $outputStream
     * @return void
     */
    public function handleInput(array $cliArguments, $outputStream)
    {
        if (!$this->streamIsWritable($outputStream)) {
            throw new \InvalidArgumentException('Output Stream must be a writable Stream');
        }

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
                    $this->printInterfaceToStream = false;
                    $this->interfaceDistiller->$arg(
                        new \SplFileObject(array_shift($cliArguments))
                    );
                    break;
                case 'excludeImplementedMethods':
                case 'excludeInheritedMethods':
                case 'excludeMagicMethods':
                case 'excludeOldStyleConstructors':
                    $this->interfaceDistiller->$arg();
                	break;
                default:
                    $options[] = $arg;
                    break;
            }
        }
        if (count($options) === 2) {
            if ($this->printInterfaceToStream) {
                $outFile = new \SplTempFileObject(-1);
                $this->interfaceDistiller->saveAs($outFile);
                $this->interfaceDistiller->distill($options[0], $options[1]);
                $outFile->rewind();
                ob_start();
                $outFile->fpassthru();
                fwrite($outputStream, ob_get_clean());
                fwrite($outputStream, PHP_EOL);
            } else {
                $this->interfaceDistiller->distill($options[0], $options[1]);
            }
            fwrite($outputStream, 'Done.' . PHP_EOL);
        } else {
            fwrite($outputStream, $this->getUsage() . PHP_EOL);
        }
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
     * @param resource $stream
     * @return bool
     */
    protected function streamIsWritable($stream)
    {
        $meta = stream_get_meta_data($stream);
        return strpos($meta['mode'], 'w') !== false;
    }

    /**
     * @return void
     */
    protected function getUsage()
    {
        return <<< TXT
Interface Distiller 1.0.0 by Gordon Oheim.

Usage: phpdistill [options] <classname> <interfacename>

  --methodsWithModifiers         A ReflectionMethod Visibility BitMask. Defaults to Public.
  --extendInterfaceFrom          Comma-separated list of Interfaces to extend.
  --excludeImplementedMethods    Will exclude all implemented methods.
  --excludeInheritedMethods      Will exclude all inherited methods.
  --excludeMagicMethods          Will exclude all magic methods.
  --excludeOldStyleConstructors  Will exclude Legacy Constructors.
  --filterMethodsByPattern       Only include methods matching PCRE pattern.
  --saveAs                       Filename to save new Interface to.
TXT;
    }
}