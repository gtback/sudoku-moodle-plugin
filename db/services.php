<?php

$functions = array(
    'mod_sudoku_start_puzzle' => array(         //web service function name
        'classname'   => 'mod_sudoku_external',  //class containing the external function
        'methodname'  => 'start_puzzle',          //external function name
        'classpath'   => 'mod/sukoku/externallib.php',  //file containing the class/external function
        'description' => 'Start a new sudoku puzzle.',    //human readable description of the web service function
        'type'        => 'write',                  //database rights of the web service function (read, write)
    ),
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'Sudoku Service' => array(
                'functions' => array ('mod_sudoku_start_puzzle'),
                'restrictedusers' => 0,
                'enabled'=>1,
        )
);
