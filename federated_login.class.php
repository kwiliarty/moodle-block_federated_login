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
        $this->content .= $this->print_login_url();
        $this->content .= $this->print_home_school();
        $this->content .= $this->print_cookie_manager();
        $this->content .= $this->print_help_link();
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

    public function print_home_school() {

        $account_home = (!empty($this->home_school)) ? $this->home_school['name'] : get_string('notset', 'block_federated_login');

        $account_home_div = html_writer::tag('div',
            get_string('accounthome', 'block_federated_login') . " $account_home",
            array('class'=>'login-account-home'));

        return $account_home_div;
    }

    public function print_login_url() {

        global $CFG;

        if (!isloggedin() || isguestuser()) {
            $url = get_login_url();
            $login_link = html_writer::link($url, get_string('login'));
            $current_status = get_string('notloggedin', 'block_federated_login');
        } else {
            $url = new moodle_url($CFG->httpswwwroot.'/login/logout.php', array('sesskey'=>sesskey()));
            $login_link = html_writer::link($url, get_string('logout'));
            $current_status = get_string('loggedin', 'block_federated_login');
        }
        $currently = get_string('currently', 'block_federated_login');
        $current_div = html_writer::tag('div', "$currently $current_status", array('class'=>'login-status-div'));
        $login_div = html_writer::tag('div', $login_link, array('class'=>'login-link-div'));
        return "$current_div\n$login_div";
    }

    public function print_cookie_manager() {

        if (empty($this->cookie_manager)) {
            return '';
        }

        $link_attributes = array('target' => '_blank');
        $cookie_manager_link = html_writer::link( $this->cookie_manager ,
            get_string('managehome', 'block_federated_login') ,
            $link_attributes );
        $cookie_manager_div = html_writer::tag('div', $cookie_manager_link, array('class'=>'cookie-manager'));
        return $cookie_manager_div;
    }

    public function print_help_link() {

        global $CFG;

        $helpurl = $CFG->block_federated_login_help_url;
        $helptext = $CFG->block_federated_login_help_text;

        if (empty($helpurl) || empty($helptext)) {
            return '';
        }

        $link = html_writer::link($helpurl, $helptext, array('target'=>'_blank'));
        $helpdiv = html_writer::tag('div', $link, array('class'=>'federated-login-help'));
        return $helpdiv;

    }

}
