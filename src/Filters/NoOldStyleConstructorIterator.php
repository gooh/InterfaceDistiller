<?php
namespace com\github\gooh\InterfaceDistiller\Filters;
class NoOldStyleConstructorIterator extends \FilterIterator
{
    /**
     * @see \FilterIterator::accept()
     * @return bool
     */
    public function accept()
    {
        if ($this->current()->getDeclaringClass()->inNamespace()) {
            return true;
        } else {
            return $this->hasMethodNamedAfterClass();
        }
    }

    /**
     * @return boolean
     */
    protected function hasMethodNamedAfterClass()
    {
        return strcasecmp(
            $this->current()->name,
            $this->current()->getDeclaringClass()->getShortName()
        ) !== 0;
    }
}