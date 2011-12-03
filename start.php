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
 * This file is responsible for producing the downloadable versions of a survey
 * module.
 *
 * @package   mod-survey
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname($_SERVER["SCRIPT_FILENAME"]))).'/config.php');
require_once('locallib.php');
require_once('lib.php');

// Check that all the parameters have been provided.

$id = required_param('id', PARAM_INT);    // Course Module ID

if (! $cm = get_coursemodule_from_id('sudoku', $id)) {
    print_error('invalidcoursemodule');
}

if (! $course = $DB->get_record("course", array("id"=>$cm->course))) {
    print_error('coursemisconf');
}

$context = get_context_instance(CONTEXT_MODULE, $cm->id);

require_login($course->id, false, $cm);

if (! $sudoku = $DB->get_record("sudoku", array("id"=>$cm->instance))) {
    print_error('invalidsudokuid', 'sudoku');
}

/// Print the page header
$PAGE->set_url('/mod/sudoku/start.php', array('id'=>$id));
$PAGE->set_title(format_string($sudoku->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

add_to_log($course->id, "sudoku", "start", "view.php?id=" . $cm->id, "$sudoku->name", $cm->id);

$attempt = sudoku_start_puzzle($sudoku, $USER->id);
//DEBUG
//$attempt_id = "TEST";

// Output starts here
echo $OUTPUT->header();

if ($sudoku->intro) { // Conditions to show the intro can change to look for own settings or whatever
    echo $OUTPUT->box(format_module_intro('sudoku', $sudoku, $cm->id), 'generalbox mod_introbox', 'sudokuintro');
}

echo $OUTPUT->heading('Starting "' . $sudoku->name . '"');

echo $OUTPUT->heading(get_string('puzzlestarted', 'sudoku'));
echo '<p>' . get_string('puzzlestarteddesc', 'sudoku') . '</p>';
echo '<p>' . $attempt->id . "_" . $USER->id . "_" . $sudoku->representation . '</p>';
echo '<p>You started the puzzle at ' . date("Y-m-d H:i:s",$attempt->starttime) . '.</p>';
// Finish the page
echo $OUTPUT->footer();
