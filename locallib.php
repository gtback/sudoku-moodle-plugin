<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Internal library of functions for module sudoku
 *
 * All the sudoku specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod
 * @subpackage sudoku
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Sukodu Status
 * @global array $SUDOKU_STATUS
 */
global $SUDOKU_STATUS;
$SUDOKU_STATUS = array (
         "0" => "Incomplete",
         "1" => "Correct",
         "2" => "Incorrect",
         "3" => "Abandoned",
        );

defined('MOODLE_INTERNAL') || die();

/**
 * Does something really useful with the passed things
 *
 * @param array $things
 * @return object
 */

function sudoku_start_puzzle($sudoku, $user)
{
    global $DB;
    global $SUDOKU_STATUS;

    $record = new stdClass();
    $record->sudoku_id = $sudoku->id;
    $record->userid = $user;
    $record->starttime = time();
    $record->status = 0; //Incomplete
    $record->hints_used = 0;
    
    return $DB->insert_record("sudoku_attempt", $record, true);
}
