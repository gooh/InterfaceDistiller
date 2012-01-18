<?php
namespace com\github\gooh\InterfaceDistiller\Filters;
class NoTraitMethodsIterator extends \FilterIterator
{
    /**
     * @see \FilterIterator::accept()
     * @return bool
     */
    public function accept()
    {
        return $this->current()->getDeclaringClass()->isTrait();
    }
}