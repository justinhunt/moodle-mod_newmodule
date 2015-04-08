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
 * The mod_page course module viewed event.
 *
 * @package    mod_NEWMODULE
 * @copyright  COPYRIGHTNOTICE
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_NEWMODULE\task;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/NEWMODULE/lib.php');

/**
 * The mod_NEWMODULE course module viewed event class.
 *
 * @package    mod_NEWMODULE
 * @since      Moodle 2.7
 * @copyright  COPYRIGHTNOTICE
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class NEWMODULE_scheduled extends \core\task\scheduled_task {    
		
	public function get_name() {
        // Shown in admin screens
        return get_string('waitinglisttask', MOD_NEWMODULE_LANG);
    }
	
	 /**
     *  Run all the tasks
     */
	 public function execute(){
		$trace = new \text_progress_trace();
        $NEWMODULE->mod_NEWMODULE_dotask($trace);
	}

}

