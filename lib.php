<?php
// This file is part of the Lessonspace plugin for Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants.
 *
 * @package   mod_lessonspace
 * @copyright 2021 Lessonspace, Inc
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->dirroot.'/mod/lessonspace/lib.php';


/**
 * Exception for 4xx response from the Lessonspace API.
 */
class lessonspace_bad_request_exception extends moodle_exception
{
    public $response = null;

    /**
     * Constructor
     *
     * @param string $response    Web service response
     * @param int    $status_code HTTP status code of the response
     */
    public function __construct($response, $status_code)
    {
        $this->response = $response;
        parent::__construct('errorapiservice', 'mod_lessonspace', '', $response);
    }
}


/**
 * Return if the plugin supports $feature.
 *
 * @param  string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function lessonspace_supports($feature)
{
    switch ($feature) {
        case FEATURE_COMPLETION_TRACKS_VIEWS:
        case FEATURE_GRADE_HAS_GRADE:
        case FEATURE_GROUPINGS:
        case FEATURE_GROUPMEMBERSONLY:
        case FEATURE_MOD_INTRO:
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_lessonspace into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object                   $spaceinstance An object from the form.
 * @param mod_lessonspace_mod_form $mform         The form.
 *
 * @return int The id of the newly inserted record.
 */
function lessonspace_add_instance($spaceinstance, mod_lessonspace_mod_form $mform = null)
{
    global $CFG, $DB;
    include_once $CFG->dirroot . '/mod/lessonspace/classes/apiservice.php';
    $service = new mod_lessonspace_api_service();

    $spaceinstance->timecreated = time();
    if ($spaceinstance->add_lock && $spaceinstance->duration > 0) {
        $tempinstance = clone $spaceinstance;
        $endtime = $tempinstance->start_time + $tempinstance->duration;
        $spaceinstance->end_time = $endtime;
    }

    $response = $service->create_space($spaceinstance);
    $spaceinstance->space_slug = $response->room_id;

    $id = $DB->insert_record('lessonspace', $spaceinstance);

    return $id;
}

/**
 * Updates an instance of the mod_lessonspace in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object                   $spaceinstance An object from the form in mod_form.php.
 * @param mod_lessonspace_mod_form $mform          The form.
 *
 * @return bool True if successful, false otherwise.
 */
function lessonspace_update_instance($spaceinstance, mod_lessonspace_mod_form $mform = null)
{
    global $DB;

    $spaceinstance->id = $spaceinstance->instance;
    $spaceinstance->timemodified = time();

    if ($spaceinstance->add_lock) {
        $tempinstance = clone $spaceinstance;
        $endtime = $tempinstance->start_time + $tempinstance->duration;
        $spaceinstance->end_time = $endtime;
    }

    return $DB->update_record('lessonspace', $spaceinstance);
}

/**
 * Removes an instance of the mod_lessonspace from the database.
 *
 * @param int $id Id of the module instance.
 *
 * @return bool True if successful, false on failure.
 */
function lessonspace_delete_instance($id)
{
    global $CFG, $DB;
    $exists = $DB->get_record('lessonspace', array('id' => $id));
    if (!$exists) {
        return false;
    }
    $DB->delete_records('lessonspace', array('id' => $id));
    return true;
}
