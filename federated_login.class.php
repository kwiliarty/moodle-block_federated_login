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
 * Manage federated logins
 *
 * @package block_federated_login
 * @copyright 2014 Smith College ITS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Definitions
 */
define('BLOCK_FEDERATED_LOGIN_DEFAULT_SCHOOL_COUNT', 5);

/**
 * The federated login
 *
 * @copyright 2014 Smith College ITS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_federated_login_handler {

    public $content;

    public $home_institution = false;

    public $home_cookie_name = '';

    public $schools = array();

    public function __construct() {
        global $CFG;

        $numberofschools = BLOCK_FEDERATED_LOGIN_DEFAULT_SCHOOL_COUNT;
        if (isset($CFG->block_federated_login_school_count)) {
            $numberofschools = $CFG->block_federated_login_school_count;
        }

        for ($i = 1; $i <= $numberofschools; $i++) {
            $id   = $CFG->block_federated_login_school_id_$i;
            $name = $CFG->block_federated_login_school_name_$i;
            $idp  = $CFG->block_federated_login_school_idp_$i;
            if (empty($id) || empty($name) || empty($idp)) { continue; }
            $this->schools[$id] = array(
                'id' => $id,
                'name' => $name,
                'idp'  => $idp
            )
        }

        if (isset($CFG->block_federated_login_home_cookie_name)) {
            $this->home_cookie_name = $CFG->block_federated_login_home_cookie_name;
        }

        if ( array_key_exists( $this->home_cookie_name , $_COOKIE )) {
            $cookie = $_COOKIE[$this->home_cookie_name];
            $this->home_institution = $cookie;
        }

    }

    public function get_content() {
        return "<pre>" . print_r($this->home_institution) . "</pre>";
    }

}
