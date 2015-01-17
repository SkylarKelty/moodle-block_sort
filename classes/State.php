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

namespace block_sort;

/**
 * State engine for block_sort.
 *
 * @package    block_sort
 * @copyright  2015 Onwards Skylar Kelty
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

defined('MOODLE_INTERNAL') || die();

class State
{
	private $_courseid;
	private $_statemap;

	/**
	 * Public constructor.
	 */
	public function __construct($courseid) {
		$this->_courseid = $courseid;
		$this->collect();
	}

	/**
	 * Collects the state of the course.
	 */
	private function collect() {
		global $DB;

		$this->_statemap = array();

		// First list all sections.
		$sections = $DB->get_records('course_sections', array(
			'course' => $this->_courseid
		));

		// Then, store order of course modules.
		foreach ($sections as $section) {
			$this->_statemap[$section->id] = explode(',', $section->sequence);
		}
	}

	/**
	 * Save the current "state" of the course.
	 */
	public function save() {
		global $DB;

		$version = (int)$DB->get_field('block_sort_state', '(COALESCE(MAX(version), 0) + 1) AS version', array(
			'courseid' => $this->_courseid
		));

		$DB->insert_record('block_sort_state', array(
			'courseid' => $this->_courseid,
			'version' => $version
		));

		$records = array();
		foreach ($this->_statemap as $section => $cmids) {
			$pos = 0;
			foreach ($cmids as $cmid) {
				$records[] = array(
					'stateid' => $version,
					'cmid' => (int)$cmid,
					'sectionid' => $section,
					'position' => $pos
				);

				$pos++;
			}
		}

		$DB->insert_records('block_sort_state_entry', $records);
	}
}