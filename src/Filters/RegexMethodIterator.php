<?php
namespace com\github\gooh\InterfaceDistiller\Filters;
class RegexMethodIterator extends \FilterIterator
{
    /**
     * @var string
     */
    protected $pcrePattern;

    /**
     * @param \Iterator $iterator
     * @param string $pcrePattern
     */
    public function __construct(\Iterator $iterator, $pcrePattern)
    {
        $this->pcrePattern = $pcrePattern;
        parent::__construct($iterator);
    }

    /**
     * @see \FilterIterator::accept()
     * @return bool
     */
    public function accept()
    {
        return preg_match($this->pcrePattern, $this->current()->name);
    }
}