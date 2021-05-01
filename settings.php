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
 * Plugin administration pages are defined here.
 *
 * @package   mod_lessonspace
 * @category  admin
 * @copyright 2021 Lessonspace, Inc
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->dirroot.'/mod/lessonspace/lib.php';

if ($hassiteconfig) {
    $settings = new admin_settingpage('modsettingslessonspace', get_string('pluginname', 'mod_lessonspace'));

    if ($ADMIN->fulltree) {
        // Test whether connection works and display result to user.
        if (!CLI_SCRIPT && $PAGE->url == $CFG->wwwroot . '/' . $CFG->admin . '/settings.php?section=modsettingslessonspace') {
            $key = 'apiconnected';
            $notifyclass = 'notifysuccess';
            $errormessage = '';
            try {
                $service = new mod_lessonspace_api_service();
                $service->get_organisation();
            } catch (moodle_exception $error) {
                $key = 'apinotconnected';
                $notifyclass = 'notifyproblem';
                $errormessage = ': '.$error->a;
            }
            $statusmessage = $OUTPUT->notification(
                get_string($key, 'lessonspace') . $errormessage,
                $notifyclass
            );
            $connectionstatus = new admin_setting_heading('mod_lessonspace/connectionstatus', $statusmessage, '');
            $settings->add($connectionstatus);
        }

        $settings->add(
            new admin_setting_configtext(
                'mod_lessonspace/apikey',
                get_string('apikey', 'mod_lessonspace'),
                get_string('apikey_description', 'mod_lessonspace'),
                '',
                PARAM_ALPHANUMEXT,
                36
            )
        );
    }
}
