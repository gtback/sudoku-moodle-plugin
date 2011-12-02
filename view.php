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
 * Prints a particular instance of sudoku
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage sudoku
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
//require_once(dirname(__FILE__).'/lib.php');

// Since we're using symlinks
require_once(dirname(dirname(dirname($_SERVER["SCRIPT_FILENAME"]))).'/config.php');
require_once(dirname($_SERVER["SCRIPT_FILENAME"]).'/lib.php');


$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$s  = optional_param('s', 0, PARAM_INT);  // sudoku instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('sudoku', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $sudoku     = $DB->get_record('sudoku', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($s) {
    $sudoku     = $DB->get_record('sudoku', array('id' => $s), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $sudoku->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('sudoku', $sudoku->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

add_to_log($course->id, 'sudoku', 'view', "view.php?id={$cm->id}", $sudoku->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/sudoku/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($sudoku->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('sudoku-'.$somevar);

// Output starts here
echo $OUTPUT->header();

if ($sudoku->intro) { // Conditions to show the intro can change to look for own settings or whatever
    echo $OUTPUT->box(format_module_intro('sudoku', $sudoku, $cm->id), 'generalbox mod_introbox', 'sudokuintro');
}

// Replace the following lines with you own code
echo $OUTPUT->heading($sudoku->name);


//NOT VERY MOODLE-like, and probably unsafe. Need to fix this.
echo "\n<table>\n";
$count = 0;
for ($orow = 0; $orow < 3; $orow++)
{
    echo "\t<tr>\n";
    for ($ocol = 0; $ocol < 3; $ocol++)
    {
        echo "\t\t<td style=\"border:2px solid black;margin:0px\"><table>\n";
        for ($irow = 0; $irow < 3; $irow++)
        {
            echo "\t\t\t<tr>\n";
            for ($icol = 0; $icol < 3; $icol++)
            {
                $char = $sudoku->representation[($orow * 3 + $irow) * 9 + ($ocol * 3 + $icol)];
                if ($char == '0')
                    $char = '&nbsp;';
                echo "\t\t\t\t<td style=\"border:1px solid black;width:20px;height:20px;\">" . $char . "</td>\n";
            }
            echo "\t\t\t</tr>\n";
        }
        echo "\t\t</table></td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";
echo $OUTPUT->single_button(new moodle_url("start.php", array('id'=>$cm->id)), get_string('start', 'sudoku'));

// Finish the page
echo $OUTPUT->footer();
