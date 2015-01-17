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
 * Sort block.
 *
 * @package    block_sort
 * @copyright  2015 Onwards Skylar Kelty
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

class block_sort extends block_base {
    /**
     * Init method.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_sort');
    }

    /**
     * We have config.
     */
    public function has_config() {
        return true;
    }

    /**
     * Allow config of specific instances.
     */
    public function instance_allow_config() {
        return true;
    }

    /**
     * We can only see this in courses.
     */
    public function applicable_formats() {
        return array('all' => false, 'course' => true);
    }

    /**
     * Get block content.
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new \stdClass;
        $this->content->text = 'Sort?';
        $this->content->footer = '';

        return $this->content;
    }
}
