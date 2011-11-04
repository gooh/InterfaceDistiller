<?php
class NoOldStyleConstructorIterator extends FilterIterator
{
    /**
     * @see FilterIterator::accept()
     * @return bool
     */
    public function accept()
    {
        return strcasecmp(
            $this->current()->name,
            $this->current()->getDeclaringClass()->getShortName()
        ) !== 0;
    }
}