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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Handles API calls to Lessonspace REST API.
 *
 * @package   mod_lessonspace
 * @copyright 2021 Lessonspace, Inc
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->dirroot . '/lib/filelib.php';

/**
 * API service class.
 *
 * @copyright 2021 Lessonspace, Inc
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_lessonspace_api_service
{
    /**
     * API base URL.
     *
     * @var string
     */
    private const BASE_URL = 'https://api.thelessonspace.com/v2/';

    private $_apikey = null;

    public function __construct()
    {
        $config = get_config('mod_lessonspace');
        $exp = '/[0-9a-f]{8}-(?:[0-9a-f]{4}-){3}[0-9a-f]{12}/';
        if (empty($config->apikey)) {
            throw new moodle_exception('errorapiservice', 'mod_lessonspace', '', get_string('errorapikeynotdefined', 'mod_lessonspace'));
        } elseif (preg_match($exp, $config->apikey) <= 0) {
            throw new moodle_exception('errorapiservice', 'mod_lessonspace', '', get_String('errorapikeyinvalid', 'mod_lessonspace'));
        } else {
            $this->_apikey=$config->apikey;
        }
    }

    public function get_organisation()
    {
        $curl = $this->_make_curl();
        $response = $curl->get(self::BASE_URL . 'my-organisation/');
        return $this->_handle_response($curl, $response);
    }

    /**
     * [create_space description]
     * @param  [type] $spaceinstance [description]
     * @return [type]                [description]
     */
    public function create_space($spaceinstance)
    {
        $curl = $this->_make_curl(true);
        $data = array(
            'id'=>$spaceinstance->space_id,
            'allow_guests'=>false
        );
        $response = $curl->post(self::BASE_URL . 'spaces/launch/', json_encode($data));

        return $this->_handle_response($curl, $response);
    }

    /**
     * [join_space description]
     * @return [type] [description]
     */
    public function join_space($spaceinstance, $isstudent)
    {
        global $USER;
        $curl = $this->_make_curl(true);
        $data = array(
            'id'=>$spaceinstance->space_id,
            'allow_guests'=>false,
            'user'=>array(
                'leader'=>!$isstudent,
                'id'=>$USER->id,
                'name'=>$USER->firstname . ' ' . $USER->lastname,
                'email'=>$USER->email
            )
        );
        if ($isstudent && $spaceinstance->add_lock) {
            $starttime = date('c', $spaceinstance->start_time);
            $endtime = date('c', $spaceinstance->end_time);
            $data['timeouts'] = array(
                'not_before'=>$starttime
            );
            if ($spaceinstance->duration > 0) {
                $data['timeouts']['not_after'] = $endtime;
            }
        }
        $response = $curl->post(self::BASE_URL . 'spaces/launch/', json_encode($data));

        return $this->_handle_response($curl, $response);
    }

    /**
     * [_handle_response description]
     * @param  [type] $response [description]
     * @return [type]           [description]
     */
    private function _handle_response(curl $curl, $response)
    {
        if ($curl->get_errno()) {
            throw new moodle_exception('errorapiservice', 'mod_lessonspace', $curl->error);
        }

        $response = json_decode($response);

        $status_code = $curl->get_info()['http_code'];

        if ($status_code >= 400) {
            return $this->_handle_error($response, $status_code);
        } else {
            return $response;
        }
    }

    /**
     * [_handle_error description]
     * @param  [type] $response    [description]
     * @param  [type] $status_code [description]
     * @return [type]              [description]
     */
    private function _handle_error(stdClass $response, $status_code)
    {
        switch ($status_code) {
            case 401:
                throw new lessonspace_bad_request_exception('Invalid API Key', $status_code);
            case 400:
            case 403:
                throw new lessonspace_bad_request_exception($response->detail, $status_code);
            default:
                $error = null;
                if ($response) {
                    $error = $response->non_field_errors[0];
                } else {
                    $error = "HTTP Status $status_code";
                }
                throw new moodle_exception('errorapiservice', 'mod_lessonspace', '', $error);
        }
    }

    /**
     * [_make_curl description]
     * @param  boolean $has_body [description]
     * @return curl            [description]
     */
    private function _make_curl($has_body = false)
    {
        $curl = new curl();

        if ($has_body) {
            $curl->setHeader('Content-Type: application/json');
        }

        $curl->setHeader('Authorization: Organisation ' . $this->_apikey);
        return $curl;
    }
}
