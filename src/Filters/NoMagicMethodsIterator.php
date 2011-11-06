<?php
namespace com\github\gooh\InterfaceDistiller\Filters;
class NoMagicMethodsIterator extends \FilterIterator
{
    /**
     * @see \FilterIterator::accept()
     * @return bool
     */
    public function accept()
    {
        return substr($this->current()->name, 0, 2) != '__';
    }
}