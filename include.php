<?php

require_once __DIR__.'/lib/Mapping/Title.php';
require_once __DIR__.'/lib/Mapping/Property.php';


CModule::AddAutoloadClasses('ez.doctrine',
    array(
        'Doctrine' => 'classes/doctrine.php',
        'DoctrineExtensions\\DoctrineTablePrefix' => 'classes/doctrine_prefix.php',
        'D' => 'classes/d.php',
        'Ez\\Doctrine\\DoctrineMenu' => 'lib/DoctrineMenu.php',
        'Ez\\Doctrine\\Mapping\\Title' => 'lib/Mapping/Title.php',
    )
);


