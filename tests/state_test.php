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
 * PHPUnit data generator tests
 *
 * @package    block_sort
 * @copyright  2015 Onwards Skylar Kelty
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * PHPUnit testcase
 *
 * @package    block_sort
 * @copyright  2015 Onwards Skylar Kelty
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
class block_sort_state_test extends advanced_testcase
{
	/**
	 * Test the observer.
	 */
    public function test_can_restore() {
        global $DB;

        $this->resetAfterTest(true);
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course();
        $this->getDataGenerator()->create_module('resource', array('course' => $course));
        $this->getDataGenerator()->create_module('resource', array('course' => $course));
        $this->getDataGenerator()->create_module('resource', array('course' => $course));

        $this->assertEquals(0, $DB->count_records('block_sort_state'));

        $state = new \block_sort\State($course->id);
        $firstversion = $state->save();

        $this->assertEquals(1, $DB->count_records('block_sort_state'));
        $this->assertEquals(3, $DB->count_records('block_sort_state_entry'));

        $this->assertTrue($state->can_restore($firstversion));

        $this->getDataGenerator()->create_module('resource', array('course' => $course));

        $state = new \block_sort\State($course->id);
        $this->assertFalse($state->can_restore($firstversion));
    }
}