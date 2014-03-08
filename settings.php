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

defined('MOODLE_INTERNAL') || die;

$settings->add(new admin_setting_configtext('block_federated_login_home_cookie_name' ,
    get_string('homecookiename', 'block_federated_login') ,
    get_string('confighomecookiename', 'block_federated_login') , ''));

$numbers2select = array();
for ($i = 0; $i <= 10; $i++) {
    $numbers2select[] = $i;
}

$settings->add(new admin_setting_configselect('block_federated_login_school_count',
    get_string('schoolcount', 'block_federated_login'),
    get_string('configschoolcount', 'block_federated_login'),
    5, $numbers2select));
