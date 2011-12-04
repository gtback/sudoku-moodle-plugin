<?php

$functions = array(
    'sudoku_start_puzzle' => array(         //web service function name
        'classname'   => 'sudoku_external',  //class containing the external function
        'methodname'  => 'start_puzzle',          //external function name
        'classpath'   => 'mod/sukoku/externallib.php',  //file containing the class/external function
        'description' => 'Start a new sudoku puzzle.',    //human readable description of the web service function
        'type'        => 'write',                  //database rights of the web service function (read, write)
    ),
);
?>
