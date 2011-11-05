<?php
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