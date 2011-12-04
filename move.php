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

$aid = required_param('a', PARAM_INT);    // AttemptID
$loc = required_param('l', PARAM_INT);    // Location
$type = required_param('t', PARAM_INT);   // Move type
$val = required_param('v', PARAM_INT);    // Value
$strat = required_param('s', PARAM_INT);  // Strategy Used

print sudoku_make_move($aid, $loc, $type, $val, $strat);
exit;
