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
defined('MOODLE_INTERNAL') || die();

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

    /** @var string HTML block content */
    public $content;
    /** @var string Name of the login_home cookie */
    public $cookiename = '';
    /** @var string Value of the login_home cookie */
    public $cookievalue = '';
    /** @var int Number of schools to be configured */
    public $numberofschools = BLOCK_FEDERATED_LOGIN_DEFAULT_SCHOOL_COUNT;
    /** @var array Schools participating in the federated login */
    public $schools = array();
    /** @var array Some details of a user's home school */
    public $homeschool = array();
    /** @var string Cookie management interface */
    public $cookiemanager = '';

    /**
     * Constructor
     */
    public function __construct() {

        $this->get_cookievalue();
        $this->get_schools();
        $this->get_homeschool();
        $this->get_cookiemanager();

    }

    /**
     * Return the block content
     *
     * @return string The content of the block
     */
    public function get_content() {
        $this->content .= $this->print_login_url();
        $this->content .= $this->print_homeschool();
        $this->content .= $this->print_cookiemanager();
        $this->content .= $this->print_help_link();
        return $this->content;;
    }

    /**
     * Gets the name for the login_home cookie and sets it if it exists
     */
    public function get_cookievalue() {

        global $CFG;

        if (isset($CFG->block_federated_login_home_cookie_name)) {
            $this->cookiename = $CFG->block_federated_login_home_cookie_name;
        }

        if ( array_key_exists( $this->cookiename , $_COOKIE )) {
            $this->cookievalue = $_COOKIE[$this->cookiename];
        }
    }

    /**
     * Sets up the array of federated schools
     */
    public function get_schools() {

        global $CFG;

        if (isset($CFG->block_federated_login_school_count)) {
            $this->numberofschools = $CFG->block_federated_login_school_count;
        }

        for ($i = 1; $i <= $this->numberofschools; $i++) {
            $idproperty = "block_federated_login_school_id_$i";
            $id   = $CFG->$idproperty;
            $nameproperty = "block_federated_login_school_name_$i";
            $name = $CFG->$nameproperty;
            $idpproperty = "block_federated_login_school_idp_$i";
            $idp  = $CFG->$idpproperty;
            if (empty($id) || empty($name) || empty($idp)) {
                continue;
            }
            $this->schools[$id] = array(
                'id' => $id,
                'name' => $name,
                'idp'  => $idp
            );
        }
    }

    /**
     * Get a user's home institution
     */
    public function get_homeschool() {
        if (empty($this->cookievalue)) {
            $this->homeschool = '';
            return;
        }
        foreach ($this->schools as $school) {
            if ($school['idp'] == $this->cookievalue) {
                $this->homeschool = $school;
                return;
            }
        }
    }

    /**
     * Set a cookiemanager if defined in settings
     */
    public function get_cookiemanager() {

        global $CFG;

        if (isset($CFG->block_federated_login_home_cookie_manager)) {
            $this->cookiemanager = $CFG->block_federated_login_home_cookie_manager;
        }
    }

    /**
     * Print a user's homeschool
     *
     * @return string HTML for a user's home school
     */
    public function print_homeschool() {

        $accounthome = (!empty($this->homeschool)) ? $this->homeschool['name'] : get_string('notset', 'block_federated_login');

        $accounthomediv = html_writer::tag('div',
            get_string('accounthome', 'block_federated_login') . " $accounthome",
            array('class' => 'login-account-home'));

        return $accounthomediv;
    }

    /**
     * Print a login url
     *
     * @return string HTML for the login URL
     */
    public function print_login_url() {

        global $CFG;

        if (!isloggedin() || isguestuser()) {
            $url = get_login_url();
            $loginlink = html_writer::link($url, get_string('login'), array('class' => 'btn btn-primary'));
            $currentstatus = get_string('notloggedin', 'block_federated_login');
        } else {
            $url = new moodle_url($CFG->httpswwwroot.'/login/logout.php', array('sesskey' => sesskey()));
            $loginlink = html_writer::link($url, get_string('logout'), array('class' => 'btn btn-primary'));
            $currentstatus = get_string('loggedin', 'block_federated_login');
        }
        $currently = get_string('currently', 'block_federated_login');
        $currentdiv = html_writer::tag('div', "$currently $currentstatus", array('class' => 'login-status-div'));
        $logindiv = html_writer::tag('div', $loginlink, array('class' => 'login-link-div'));
        return "$currentdiv\n$logindiv";
    }

    /**
     * Print the cookie manager link
     *
     * @return string HTML for the cookie manager link
     */
    public function print_cookiemanager() {

        if (empty($this->cookiemanager)) {
            return '';
        }

        $linkattributes = array('target' => '_blank');
        $cookiemanagerlink = html_writer::link( $this->cookiemanager ,
            get_string('managehome', 'block_federated_login') ,
            $linkattributes );
        $cookiemanagerdiv = html_writer::tag('div', $cookiemanagerlink, array('class' => 'cookie-manager'));
        return $cookiemanagerdiv;
    }

    /**
     * Print the help link
     *
     * @return string HTML for the help link
     */
    public function print_help_link() {

        global $CFG;

        $helpurl = $CFG->block_federated_login_help_url;
        $helptext = $CFG->block_federated_login_help_text;

        if (empty($helpurl) || empty($helptext)) {
            return '';
        }

        $link = html_writer::link($helpurl, $helptext, array('target' => '_blank'));
        $helpdiv = html_writer::tag('div', $link, array('class' => 'federated-login-help'));
        return $helpdiv;

    }

}
