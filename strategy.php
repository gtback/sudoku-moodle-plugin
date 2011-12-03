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

$id = optional_param('id', -1, PARAM_INT);    // Course Module ID

if ($id >= 0)
{
    if (! $strategy = $DB->get_record("sudoku_strategy", array("id"=>$id))) {
        print_error('unknownstrategy', 'sudoku');
    }

    /// Print the page header
    $PAGE->set_url('/mod/sudoku/strategy.php', array('id'=>$id));
    $PAGE->set_title(format_string($strategy->name));
    $PAGE->set_heading(format_string("Sudoku Strategies"));
    
    // Output starts here
    echo $OUTPUT->header();
    echo $OUTPUT->heading($strategy->name);
    echo $strategy->description;
}
else
{
    $PAGE->set_url('/mod/sudoku/strategy.php');
    $PAGE->set_title(format_string("Sudoku Strategies"));
    $PAGE->set_heading(format_string("Sudoku Strategies"));
    
    // Output starts here
    echo $OUTPUT->header();
    
    echo "<p>List of strategies will go here.</p>";
    
    $strategies =  $DB->get_records("sudoku_strategy");
    
    echo "<ul>";
    foreach($strategies as $strategy)
    {
        echo '<li><a href="strategy.php?id=' . $strategy->id . '">' . $strategy->name . "</a></li>";
    }
    echo "</ul>";
}

echo '<p>Information from <a href="http://www.sudokuessentials.com/sudoku_tips.html">SudokuEssentials.com</a>.</p>';

echo $OUTPUT->footer();