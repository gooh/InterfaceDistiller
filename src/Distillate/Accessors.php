<?php
namespace com\github\gooh\InterfaceDistiller\Distillate;
//@codeCoverageIgnoreStart
interface Accessors
{
    /**
     * @return string
     */
    public function getInterfaceName();

    /**
     * @return array
     */
    public function getInterfaceMethods();

    /**
     * @return string
     */
    public function getExtendingInterfaces();
}
//@codeCoverageIgnoreEnd
