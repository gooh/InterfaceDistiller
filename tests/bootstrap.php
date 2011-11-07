<?php
set_include_path(
    sprintf('%s%s%s', realpath(__DIR__ . '/..'), PATH_SEPARATOR, get_include_path())
);
require 'src/autoload.php';
require 'tests/unit/Filters/FilterIteratorTestCase.php';

