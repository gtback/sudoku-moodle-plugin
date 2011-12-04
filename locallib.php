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

global $SUDOKU_MOVE_TYPE;
$SUDOKU_MOVE_TYPE = array (
         "0" => "Set Value",
         "1" => "Clear Value",
         "2" => "Add Possible",
         "3" => "Remove Possible",
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
    
    $attempt = $DB->get_record('sudoku_attempt', 
            array('sudoku_id' => $sudoku->id, 'userid' => $user), '*');
    
    if ($attempt)
    {
        return $attempt;
    }
    else
    {
        $record = new stdClass();
        $record->sudoku_id = $sudoku->id;
        $record->userid = $user;
        $record->starttime = time();
        $record->status = 0; //Incomplete
        $record->hints_used = 0;

        $record->id = $DB->insert_record("sudoku_attempt", $record, true);

        return $record;
    }
}

function sudoku_complete_puzzle($attempt_id, $status)
{
    global $DB;
    global $SUDOKU_STATUS;
    
    if (! $attempt = $DB->get_record("sudoku_attempt", array("id"=>$attempt_id))) {
        return "ERROR Invalid Attempt";
    }

    // end time should be null and status should be "in progress"
    if ($attempt->endtime || $attempt->status != 0)
    {
        return "ERROR Puzzle Already Complete";
    }

    $attempt->endtime = time();
    $attempt->status = $status;

    if ($DB->update_record('sudoku_attempt', $attempt))
    {
        $status_comment =  " - " .$SUDOKU_STATUS[$attempt->status];
        sudoku_log_message($attempt, 'complete', $status_comment);
        
        return "OK";
    }
    else
    {
        return "ERROR Unable to mark puzzle as complete";
    }
}

function sudoku_use_hint($attempt_id)
{
    global $DB;
    
    if (! $attempt = $DB->get_record("sudoku_attempt", array("id"=>$attempt_id))) {
        return "ERROR Invalid Attempt";
    }

    // end time should be null and status should be "in progress"
    if ($attempt->endtime || $attempt->status != 0)
    {
        return "ERROR Puzzle Already Complete";
    }

    $attempt->hints_used++;

    if ($DB->update_record('sudoku_attempt', $attempt))
    {
        sudoku_log_message($attempt, 'hint used');
        
        return "OK";
    }
    else
    {
        return "ERROR Unable to mark hint used";
    }
}

function sudoku_make_move($attempt_id, $loc, $type, $val, $strat)
{
    global $DB;
    global $SUDOKU_MOVE_TYPE;
    
    if (! $attempt = $DB->get_record("sudoku_attempt", array("id"=>$attempt_id))) {
        return "ERROR Invalid Attempt";
    }

    // end time should be null and status should be "in progress"
    if ($attempt->endtime || $attempt->status != 0)
    {
        return "ERROR Puzzle Already Complete";
    }
    
    $record = new stdClass();
    $record->attempt_id = $attempt_id;
    $record->strategy_used = $strat;
    $record->location = $loc;
    $record->move_value = $val; //Incomplete
    $record->move_type = $type;
    $record->time = time();

    if ($DB->insert_record("sudoku_move", $record, true))
    {
        $strategy = $DB->get_record("sudoku_strategy", array("id"=>$strat));
        
        $message = " - " . $SUDOKU_MOVE_TYPE[$type] . " ($val) @$loc - $strategy->name";
        sudoku_log_message($attempt, 'move', $message );
        
        return "OK";
    }
    else
    {
        return "ERROR Unable to record move";
    }
}

function sudoku_log_message($attempt, $log_message, $comment="")
{
    global $DB;
    
    $sudoku = $DB->get_record("sudoku", array("id" => $attempt->sudoku_id), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $sudoku->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('sudoku', $sudoku->id, $course->id, false, MUST_EXIST);

    $info = $sudoku->name . $comment;
    add_to_log($course->id, "sudoku", $log_message, "view.php?id=" . $cm->id, 
           $info , $cm->id);
}
