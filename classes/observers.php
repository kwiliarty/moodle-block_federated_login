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
 * This file contains an observer to help log federated logins.
 *
 * @package    block_federated_login
 * @copyright  2015 Kevin Wiliarty (kevin.wiliarty@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_federated_login;

defined('MOODLE_INTERNAL') || die();

/**
 * Observer class for login events
 *
 * @package    block_federated_login
 * @copyright  2018 Kevin Wiliarty
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observers {

    /**
     * User logged in
     *
     * @param \core\event\user_loggedin $event
     * @return void
     */
    public static function federated_user_loggedin(\core\event\user_loggedin $event) {

        global $CFG, $DB;

        if (empty($CFG->block_federated_login_logcookieuse)) {
            return true;
        }

        $user = $DB->get_record('user', array('id' => $event->userid));
        $auth = $user->auth;

        $federatedcookiename = $CFG->block_federated_login_home_cookie_name;
        $federatedcookiename = (empty($federatedcookiename)) ? '_redirect_user_idp' : $federatedcookiename;
        $federatedcookievalue = "No default home";
        if (array_key_exists($federatedcookiename, $_COOKIE)) {
            $federatedcookievalue = $_COOKIE[$federatedcookiename];
        }

        $newevent = event\federated_user_loggedin::create(array(
            'userid'   => $event->userid,
            'objectid' => $event->userid,
            'other'    => array(
                'defaulthome' => $federatedcookievalue,
                'username'    => $event->other['username'],
                'auth'        => $auth,
            ),
        ));
        $newevent->trigger();
        return true;
    }
}
