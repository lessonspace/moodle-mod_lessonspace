<?php
// This file is part of the Lessonspace plugin for Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WIT HOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Contains class mod_lessonspace\privacy\provider.
 *
 * @package   mod_lessonspace
 * @copyright 2021 Lessonspace, Inc
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_lessonspace\privacy;

use core_privacy\local\metadata\collection;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem implementation for mod_lessonspace.
 *
 * @copyright 2021 Lessonspace, Inc
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\data_provider
{
    /**
     * Return the fields which contain personal data.
     *
     * @param collection $items a reference to the collection to use to store the metadata.
     * @return collection the updated collection of metadata items.
     */
    public static function get_metadata(collection $collection) : collection
    {
        $collection->add_external_location_link(
            'lessonspace_api',
            [
                'userid' => 'privacy:metadata:lessonspace_api:userid',
                'name' => 'privacy:metadata:lessonspace_api:name',
                'email' => 'privacy:metadata:lessonspace_api:email'
            ],
            'privacy:metadata:lessonspace_api'
        );
        return $collection;
    }
}
