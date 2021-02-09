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
 * Prints an instance of mod_lessonspace.
 *
 * @package   mod_lessonspace
 * @copyright 2021 Lessonspace, Inc
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require __DIR__ . '/../../config.php';
require_once __DIR__ . '/lib.php';

// Course_module ID, or
$id = optional_param('id', 0, PARAM_INT);

// ... module instance id.
$l = optional_param('l', 0, PARAM_INT);

if ($id) {
    $cm             = get_coursemodule_from_id('lessonspace', $id, 0, false, MUST_EXIST);
    $course         = $DB->get_record('course', array( 'id' => $cm->course ), '*', MUST_EXIST);
    $lessonspace = $DB->get_record('lessonspace', array( 'id' => $cm->instance ), '*', MUST_EXIST);
} elseif ($l) {
    $lessonspace = $DB->get_record('lessonspace', array( 'id' => $n ), '*', MUST_EXIST);
    $course         = $DB->get_record('course', array( 'id' => $lessonspace->course ), '*', MUST_EXIST);
    $cm             = get_coursemodule_from_instance('lessonspace', $lessonspace->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('missingidandcmid', 'mod_lessonspace'));
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
$ismanager = has_capability('mod/lessonspace:addinstance', $context);

$event = \mod_lessonspace\event\course_module_viewed::create(
    array(
        'objectid' => $lessonspace->id,
        'context'  => $context,
    )
);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('lessonspace', $lessonspace);
$event->trigger();

$PAGE->set_url('/mod/lessonspace/view.php', array( 'id' => $cm->id ));
$PAGE->set_title(format_string($lessonspace->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$available = true;
$finished = false;
if ($lessonspace->add_lock) {
    $now = time();
    $available = $now >= $lessonspace->start_time;

    if ($lessonspace->duration > 0) {
        $available = $available && $now <= $lessonspace->end_time;
        $finished = $now > $lessonspace->end_time;
    }
}

// UI
echo $OUTPUT->header();

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_view';

$table->align = array('center', 'left');
$numcolumns = 2;

if ($available || $ismanager) {
    $buttonhtml = html_writer::tag(
        'button',
        get_string('joinspace', 'mod_lessonspace'),
        array('type' => 'submit', 'class' => 'btn btn-primary')
    );
    $aurl = new moodle_url('/mod/lessonspace/space.php', array('id' => $cm->id));
    $buttonhtml .= html_writer::input_hidden_params($aurl);
    $link = html_writer::tag('form', $buttonhtml, array('action' => $aurl->out_omit_querystring(), 'target' => '_blank'));

    $title = new html_table_cell($link);
    $title->header = true;
    $title->colspan = $numcolumns;
    $table->data[] = array($title);

    if (!$available && $ismanager) {
        $explanation = new html_table_cell(html_writer::div(get_string('managerviewunavailablespaceexplanation', 'mod_lessonspace')));
        $explanation->header = true;
        $explanation->colspan = $numcolumns;
        $table->data[] = array($explanation);
    }
} elseif ($finished) {
    $explanation = new html_table_cell(html_writer::div(get_string('spacefinishedexplanation', 'mod_lessonspace')));
    $explanation->header = true;
    $explanation->colspan = $numcolumns;
    $table->data[] = array($explanation);
}

if (!empty($lessonspace->intro)) {
    $description = new html_table_cell(html_writer::div($lessonspace->intro));
    $description->colspan = $numcolumns;
    $table->data[] = array($description);
}

if ($ismanager) {
    $table->data[] = array('<b>'.get_string('spaceid', 'mod_lessonspace').'</b>', $lessonspace->space_id);
}

if ($lessonspace->add_lock) {
    $table->data[] = array('<b>'.get_string('date', 'mod_lessonspace').'</b>', date('l, jS F, Y', $lessonspace->start_time));
    $table->data[] = array('<b>'.get_string('starttime', 'mod_lessonspace').'</b>', date('H:i', $lessonspace->start_time));

    if ($lessonspace->duration > 0) {
        $table->data[] = array('<b>'.get_string('endtime', 'mod_lessonspace').'</b>', date('H:i', $lessonspace->end_time));
        $table->data[] = array(
            '<b>'.get_string('duration', 'mod_lessonspace').'</b>',
            get_string('viewduration', 'mod_lessonspace', $lessonspace->duration/3600)
        );
    }
}

echo html_writer::table($table);

echo $OUTPUT->footer();
