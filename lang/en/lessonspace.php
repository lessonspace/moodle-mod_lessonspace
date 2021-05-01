<?php

// This file is part of Moodle - https://moodle.org/
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
 * Plugin strings are defined here.
 *
 * @package   mod_lessonspace
 * @category  string
 * @copyright 2021 Lessonspace (Pty) Ptd
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['activityname'] = 'Activity Name';
$string['addlock'] = 'Only allow access during specified time.';
$string['addlock_help'] = 'Determines whether or not access to the Space will be locked of to only a certain time period. If this is left unchecked students will be able to enter at any time. Leave this unchecked for recurring lessons.';
$string['apiconnected'] = 'Successfully connected to Lessonspace API';
$string['apikey'] = 'API Key';
$string['apikey_description'] = 'Your organisation\'s Lessonspace API Key which you can find in the developer tab of your Lessonspace dashboard settings.';
$string['apinotconnected'] = 'Unable to connect to Lessonspace API';

$string['date'] = 'Date';
$string['duration'] = 'Duration';
$string['duration_help'] = 'Determines the duration that the space can be accessed for. I.e. once the duration has elapsed past the start time no students will be able to enter the space. Setting a negative or zero duration will mean that there isn\'t an end time on the Space.';

$string['endtime'] = 'End time';
$string['errorapikeyinvalid'] = 'Invalid Lessonspace API key';
$string['errorapikeynotdefined'] = 'Lessonspace API key not set';
$string['errorstarttimeinthepast'] = 'Start time must be a date in the future';
$string['extrasettings'] = 'Looking to edit feature, theme or locale settings of your Spaces? Make sure to checkout your <a href="https://www.thelessonspace.com/settings/spaces" target="_blank">Space Settings</a> on your Lessonspace Dashboard';

$string['indicator:cognitivedepth'] = 'Lessonspace cognitive';
$string['indicator:cognitivedepth_help'] = 'This indicator is based on the cognitive depth reached by the student in a Lessonspace activity.';
$string['indicator:socialbreadth'] = 'Lessonspace social';
$string['indicator:socialbreadth_help'] = 'This indicator is based on the social breadth reached by the student in a Lessonspace activity.';

$string['joinspace'] = 'Join Space';

$string['lessonspace:addinstance'] = 'Add a new Lessonspace Space';
$string['lessonspace:view'] = 'View Lessonspace Space';

$string['managerviewunavailablespaceexplanation'] = 'As a manager you may still join this space. Non managers are no longer able to join this space and will not see the "Join Space" button.';
$string['missingidandcmid'] = 'You must specify a course_module ID or an instance ID';
$string['modulename'] = 'Lessonspace Space';
$string['modulenameplural'] = 'Lessonspace Spaces';
$string['modulename_help'] = 'The best way to teach online. Teach live, one-on-one, or with a group, using the most versatile collaborative space for online lessons.';

$string['pluginadministration'] = 'Manage Lessonspace Space';
$string['pluginname'] = 'Lessonspace';
$string['privacy:metadata:lessonspace_api'] = 'In order to integrate with the Lessonspace API correctly, user data needs to be exchanged with the API.';
$string['privacy:metadata:lessonspace_api:email'] = 'Emails are sent to API when joining a space in order correctly identify users on Lessonspace and provide accurate session tracking.';
$string['privacy:metadata:lessonspace_api:name'] = 'Names are sent to our API when joing a space in order to allow for identification of the user when entering the Space.';
$string['privacy:metadata:lessonspace_api:userid'] = 'User IDs are sent to our API when joinging a space in order to uniquely identify users in a space.';

$string['search:activity'] = 'Lessonspace - activity information';
$string['spacefinishedexplanation'] = 'This activity is finished and this space cannot be entered.';
$string['spaceid'] = 'Space ID';
$string['spaceid_help'] = 'The ID of the space. <b>NOTE:</b> This should be unique across all Spaces. Using the ID of an existing Space will not create a new space but will simply use the existing Space with that ID.';
$string['starttime'] = 'Start Time';
$string['starttime_help'] = 'Determines the earliest time that students can enter the Space.';

$string['viewduration'] = '{$a} hour(s)';
