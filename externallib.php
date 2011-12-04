<?php
require_once("$CFG->libdir/externallib.php");
 
class mod_sudoku_external extends external_api {
 
    public static function start_puzzle_parameters() {
        return new external_function_parameters(
            array(
                'sudoku' => new external_value(PARAM_INT, 'sudoku puzzle id')
            )
        );
    }
    
    public static function start_puzzle_returns() {
        return new external_value(PARAM_TEXT, "Puzzle_code");
    }
    
    public static function start_puzzle($sudoku) { //Don't forget to set it as static
        global $CFG, $DB, $USER;
        
        require_once("$CFG->dirroot/mod/sudoku/lib.php");
        require_once("$CFG->dirroot/mod/sudoku/locallib.php");
 
        $transaction = $DB->start_delegated_transaction(); //If an exception is thrown in the below code, all DB queries in this code will be rollback.
 
        sudoku_start_puzzle($sudoku, $USER->ID);
        
        $transaction->allow_commit();
    }
}