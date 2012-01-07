<?php
class DistillTestClassWithTrait
{
    use TestTrait;
}

trait TestTrait
{
    public function traitMethod() {}
}