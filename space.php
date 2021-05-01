<?php
// This file is part of the Lessonspace plugin for Moodle - http://moodle.org/
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
 * Join the Space.
 *
 * @package    mod_lessonspace
 * @copyright  2021 Lessonspace, Inc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once dirname(dirname(dirname(__FILE__))).'/config.php';
require_once dirname(__FILE__).'/lib.php';

// Course_module ID.
$id = required_param('id', PARAM_INT);
if ($id) {
    $cm         = get_coursemodule_from_id('lessonspace', $id, 0, false, MUST_EXIST);
    $course     = get_course($cm->course);
    $lessonspace  = $DB->get_record('lessonspace', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    print_error('You must specify a course_module ID');
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/lessonspace/space.php', array( 'id' => $cm->id ));
$PAGE->set_title(format_string($lessonspace->name));
$PAGE->set_pagelayout('redirect'); // We don't want any content on this page

require_capability('mod/lessonspace:view', $context);

// Track completion viewed.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// Record user's clicking join.
\mod_lessonspace\event\join_space_button_clicked::create(array('context' => $context, 'objectid' => $lessonspace->id, 'other' =>
        array('cmid' => $id, 'spaceid' => (int) $lessonspace->space_id)))->trigger();

$isstudent = !has_capability('mod/lessonspace:addinstance', $context);

$service = new mod_lessonspace_api_service();
$response = $service->join_space($lessonspace, $isstudent);
$starttag = html_writer::start_tag('iframe', array('ref' => 'frame', 'src' => $response->client_url, 'allow' => 'camera; microphone; display-capture', 'frameBorder' => '0', 'style' => 'width: 100%; height: 100%; margin: 0; position: absolute; display: block; top: 0; left: 0; padding: 0;', 'allowfullscreen' => 'true'));
$endtag = html_writer::end_tag('iframe');
echo $OUTPUT->header();
echo $starttag . $endtag;
echo $OUTPUT->footer();
