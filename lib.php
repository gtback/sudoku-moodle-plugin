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
 * Library of interface functions and constants for module sudoku
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the sudoku specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod
 * @subpackage sudoku
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/** example constant */
//define('sudoku_ULTIMATE_ANSWER', 42);

////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function sudoku_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_INTRO:         return true;
        default:                        return null;
    }
}

/**
 * Saves a new instance of the sudoku into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $sudoku An object from the form in mod_form.php
 * @param mod_sudoku_mod_form $mform
 * @return int The id of the newly inserted sudoku record
 */
function sudoku_add_instance(stdClass $sudoku, mod_sudoku_mod_form $mform = null) {
    global $DB;

    $sudoku->timecreated = time();

    # You may have to add extra stuff in here #

    return $DB->insert_record('sudoku', $sudoku);
}

/**
 * Updates an instance of the sudoku in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $sudoku An object from the form in mod_form.php
 * @param mod_sudoku_mod_form $mform
 * @return boolean Success/Fail
 */
function sudoku_update_instance(stdClass $sudoku, mod_sudoku_mod_form $mform = null) {
    global $DB;

    $sudoku->timemodified = time();
    $sudoku->id = $sudoku->instance;

    # You may have to add extra stuff in here #

    return $DB->update_record('sudoku', $sudoku);
}

/**
 * Removes an instance of the sudoku from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function sudoku_delete_instance($id) {
    global $DB;

    if (! $sudoku = $DB->get_record('sudoku', array('id' => $id))) {
        return false;
    }

    # Delete any dependent records here #

    $DB->delete_records('sudoku', array('id' => $sudoku->id));

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return stdClass|null
 */
function sudoku_user_outline($course, $user, $mod, $sudoku) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return string HTML
 */
function sudoku_user_complete($course, $user, $mod, $sudoku) {
    return '';
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in sudoku activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 */
function sudoku_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Returns all activity in sudokus since a given time
 *
 * @param array $activities sequentially indexed array of objects
 * @param int $index
 * @param int $timestart
 * @param int $courseid
 * @param int $cmid
 * @param int $userid defaults to 0
 * @param int $groupid defaults to 0
 * @return void adds items into $activities and increases $index
 */
function sudoku_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@see sudoku_get_recent_mod_activity()}

 * @return void
 */
function sudoku_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function sudoku_cron () {
    return true;
}

/**
 * Returns an array of users who are participanting in this sudoku
 *
 * Must return an array of users who are participants for a given instance
 * of sudoku. Must include every user involved in the instance,
 * independient of his role (student, teacher, admin...). The returned
 * objects must contain at least id property.
 * See other modules as example.
 *
 * @param int $sudokuid ID of an instance of this module
 * @return boolean|array false if no participants, array of objects otherwise
 */
function sudoku_get_participants($sudokuid) {
    return false;
}

/**
 * Returns all other caps used in the module
 *
 * @example return array('moodle/site:accessallgroups');
 * @return array
 */
function sudoku_get_extra_capabilities() {
    return array();
}

////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////

/**
 * Is a given scale used by the instance of sudoku?
 *
 * This function returns if a scale is being used by one sudoku
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $sudokuid ID of an instance of this module
 * @return bool true if the scale is used by the given sudoku instance
 */
function sudoku_scale_used($sudokuid, $scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('sudoku', array('id' => $sudokuid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of sudoku.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param $scaleid int
 * @return boolean true if the scale is used by any sudoku instance
 */
function sudoku_scale_used_anywhere($scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('sudoku', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the give sudoku instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $sudoku instance object with extra cmidnumber and modname property
 * @return void
 */
function sudoku_grade_item_update(stdClass $sudoku) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $item = array();
    $item['itemname'] = clean_param($sudoku->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    $item['grademax']  = $sudoku->grade;
    $item['grademin']  = 0;

    grade_update('mod/sudoku', $sudoku->course, 'mod', 'sudoku', $sudoku->id, 0, null, $item);
}

/**
 * Update sudoku grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $sudoku instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @return void
 */
function sudoku_update_grades(stdClass $sudoku, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $grades = array(); // populate array of grade objects indexed by userid

    grade_update('mod/sudoku', $sudoku->course, 'mod', 'sudoku', $sudoku->id, 0, $grades);
}

////////////////////////////////////////////////////////////////////////////////
// File API                                                                   //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function sudoku_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * Serves the files from the sudoku file areas
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return void this should never return to the caller
 */
function sudoku_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

////////////////////////////////////////////////////////////////////////////////
// Navigation API                                                             //
////////////////////////////////////////////////////////////////////////////////

/**
 * Extends the global navigation tree by adding sudoku nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the sudoku module instance
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function sudoku_extend_navigation(navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {
}

/**
 * Extends the settings navigation with the sudoku settings
 *
 * This function is called when the context for the page is a sudoku module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $sudokunode {@link navigation_node}
 */
function sudoku_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $sudokunode=null) {
}
