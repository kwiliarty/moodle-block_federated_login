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
 * The federated_user_loggedin event.
 *
 * @package block_federated_login
 * @copyright 2015 Kevin Wiliarty <kevin.wiliarty@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_federated_login\event;
defined('MOODLE_INTERNAL') || die;
/**
 * The federated_user_loggedin event class.
 *
 * @since Moodle 2.8
 * @copyright 2014 Kevin Wiliarty <kevin.wiliarty@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class federated_user_loggedin extends \core\event\base {
    /**
     * Set up some logging parameters.
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'user';
        $this->context = \context_system::instance();
    }

    /**
     * Return the 'user logged in' string.
     *
     * @return string 'User logged in'
     */
    public static function get_name() {
        return get_string('event_federated_user_loggedin', 'block_federated_login');
    }

    /**
     * Return data for logging.
     *
     * @return string Data for logging
     */
    public function get_description() {
        return "user={$this->other['username']}, auth={$this->other['auth']}, default home={$this->other['defaulthome']}";
    }

    /**
     * Return the login URL
     *
     * @return object Moodle login URL
     */
    public function get_url() {
        return new \moodle_url(get_login_url());
    }
}
