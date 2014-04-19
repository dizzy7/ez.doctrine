<?php

CModule::AddAutoloadClasses('ez.doctrine',
    array(
        'Doctrine' => 'classes/doctrine.php',
        'DoctrineExtensions\\DoctrineTablePrefix' => 'classes/doctrine_prefix.php',
        'D' => 'classes/d.php'
    )
);


