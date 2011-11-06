<?php
namespace com\github\gooh\InterfaceDistiller\Filters;
class NoInheritedMethodsIterator extends \FilterIterator
{
    /**
     * @var string
     */
    protected $reflectedClass;

    /**
     * @param \Iterator $iterator
     * @param \ReflectionClass $reflectedClass
     */
    public function __construct(\Iterator $iterator, \ReflectionClass $reflectedClass)
    {
        $this->reflectedClass = $reflectedClass;
        parent::__construct($iterator);
    }

    /**
     * @see \FilterIterator::accept()
     * @return bool
     */
    public function accept()
    {
        return $this->reflectedClass->name === $this->current()->class;
    }
}