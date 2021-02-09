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
 * The main mod_lessonspace configuration form.
 *
 * @package   mod_lessonspace
 * @copyright 2021 Lessonspace, Inc
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->dirroot.'/course/moodleform_mod.php';

/**
 * Module instance settings form.
 *
 * @package   mod_lessonspace
 * @copyright 2021 Lessonspace (Pty) Ptd
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lessonspace_mod_form extends moodleform_mod
{

    /**
     * Defines forms elements
     */
    public function definition()
    {
        global $CFG;

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are shown.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('activityname', 'mod_lessonspace'), array('size' => '64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 300), 'maxlength', 300, 'client');

        // Adding the standard "name" field.
        $mform->addElement('text', 'space_id', get_string('spaceid', 'mod_lessonspace'), array('size' => '64'));
        $mform->setType('space_id', PARAM_TEXT);
        $mform->addRule('space_id', null, 'required', null, 'client');
        $mform->addRule('space_id', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('space_id', 'spaceid', 'mod_lessonspace');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        $mform->addElement('advcheckbox', 'add_lock', get_string('addlock', 'mod_lessonspace'));
        $mform->addHelpButton('add_lock', 'addlock', 'mod_lessonspace');

        // Add date/time.
        $mform->addElement('date_time_selector', 'start_time', get_string('starttime', 'mod_lessonspace'));
        $mform->disabledIf('start_time', 'add_lock', 'notchecked');
        $mform->addHelpButton('start_time', 'starttime', 'mod_lessonspace');

        // Add duration.
        $mform->addElement('duration', 'duration', get_string('duration', 'mod_lessonspace'));
        $mform->setDefault('duration', array('number' => 0, 'timeunit' => 3600));
        $mform->disabledIf('duration', 'add_lock', 'notchecked');
        $mform->addHelpButton('duration', 'duration', 'mod_lessonspace');

        $mform->addElement(
            'html',
            '<div style="margin-bottom: 20px; font-weight: bold;">'.get_string('extrasettings', 'mod_lessonspace').'</div>'
        );

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();
        $mform->setDefault('grade', false);

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();
        $this->apply_admin_defaults();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }

    /**
     * Enforce validation rules here
     *
     * @param object $data Post data to validate
     *
     * @return array
     **/
    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);
        return $errors;
    }
}
