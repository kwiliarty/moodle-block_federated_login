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

$plugin->component = 'block_federated_login';
$plugin->version = 2018060600; // Plugin released on June 8, 2018.
$plugin->requires = 2017051500; // Moodle 3.3 is required.
$plugin->maturity = MATURITY_STABLE; // Ready for use on production sites.
$plugin->release = 'v1.2.0';
