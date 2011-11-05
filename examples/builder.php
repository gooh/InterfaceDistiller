<?php
$distiller = new InterfaceDistiller;
$distiller
    ->fromClass('SomeClass')
    ->intoInterface('SomeInterface')
    ->extendInterfaceFrom('Countable')
    ->excludePublicMethods()
    ->excludeProtectedMethods()
    ->excludePrivateMethods()
    ->excludeImplementedMethods()
    ->excludeInheritedMethods()
    ->excludeTraitMethods()
    ->excludeMagicMethods()
    ->excludeOldStyleConstructors()
    ->filterMethodsByPcrePattern('(^[f].*)')
    ->saveAs('SomeInterface.php')
    ->distill();