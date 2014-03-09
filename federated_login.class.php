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
    public $cookie_name = '';
    public $cookie_value = '';
    public $numberofschools = BLOCK_FEDERATED_LOGIN_DEFAULT_SCHOOL_COUNT;
    public $schools = array();
    public $home_school = array();
    public $cookie_manager = '';

    public function __construct() {

        $this->get_cookie_value();
        $this->get_schools();
        $this->get_home_school();
        $this->get_cookie_manager();

    }

    public function get_content() {
        $this->content = (!empty($this->home_school)) ? "Login via: " . $this->home_school['name'] : "Not set";
        $this->content .= $this->print_cookie_manager();
        return $this->content;;
    }

    public function get_cookie_value() {

        global $CFG;

        if (isset($CFG->block_federated_login_home_cookie_name)) {
            $this->cookie_name = $CFG->block_federated_login_home_cookie_name;
        }

        if ( array_key_exists( $this->cookie_name , $_COOKIE )) {
            $this->cookie_value = $_COOKIE[$this->cookie_name];
        }
    }

    public function get_schools() {

        global $CFG;

        if (isset($CFG->block_federated_login_school_count)) {
            $this->numberofschools = $CFG->block_federated_login_school_count;
        }

        for ($i = 1; $i <= $this->numberofschools; $i++) {
            $id_property = "block_federated_login_school_id_$i";
            $id   = $CFG->$id_property;
            $name_property = "block_federated_login_school_name_$i";
            $name = $CFG->$name_property;
            $idp_property = "block_federated_login_school_idp_$i";
            $idp  = $CFG->$idp_property;
            if (empty($id) || empty($name) || empty($idp)) { continue; }
            $this->schools[$id] = array(
                'id' => $id,
                'name' => $name,
                'idp'  => $idp
            );
        }
    }

    public function get_home_school() {
        if (empty($this->cookie_value)) {
            $this->home_school = '';
            return;
        }
        foreach ($this->schools as $school) {
            if ($school['idp'] == $this->cookie_value) {
                $this->home_school = $school;
                return;
            }
        }
    }

    public function get_cookie_manager() {

        global $CFG;

        if (isset($CFG->block_federated_login_home_cookie_manager)) {
            $this->cookie_manager = $CFG->block_federated_login_home_cookie_manager;
        }
    }

    public function print_cookie_manager() {

        $link_attributes = array('target' => '_blank');
        $cookie_manager_link = html_writer::link( $this->cookie_manager ,
            get_string('managehome', 'block_federated_login') ,
            $link_attributes );
        $cookie_manager_div = html_writer::tag('div', $cookie_manager_link, array('class'=>'cookie-manager'));
        return $cookie_manager_div;
    }

}
