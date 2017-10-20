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
 * Lib file for sumar_mob theme
 *
 * @package    theme
 * @subpackage sumar_mob
 * @copyright  John Stabinger
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Allow AJAX updating of the user defined columns for tablets or not
 *
 * @param moodle_page $page
 */
function sumar_mob_initialise_colpos(moodle_page $page) {
    user_preference_allow_ajax_update('theme_sumar_mob_chosen_colpos', PARAM_ALPHA);
}

/**
 * Get the user preference for columns for tablets or not
 *
 * @param string $default
 * @return mixed
 */
function sumar_mob_get_colpos($default = 'on') {
    return get_user_preferences('theme_sumar_mob_chosen_colpos', $default);
}