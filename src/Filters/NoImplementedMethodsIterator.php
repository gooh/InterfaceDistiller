<?php
namespace com\github\gooh\InterfaceDistiller\Filters;
class NoImplementedMethodsIterator extends \FilterIterator
{
    /**
     * @see \FilterIterator::accept()
     * @return bool
     */
    public function accept()
    {
        try{
            return !interface_exists($this->current()->getPrototype()->class);
        } catch (\ReflectionException $e) {
            return true;
        }
    }
}