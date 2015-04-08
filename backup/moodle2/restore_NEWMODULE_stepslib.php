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
 * @package   mod_NEWMODULE
 * @copyright 2014 Justin Hunt poodllsupport@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
 require_once($CFG->dirroot . '/mod/NEWMODULE/lib.php');

/**
 * Define all the restore steps that will be used by the restore_NEWMODULE_activity_task
 */

/**
 * Structure step to restore one NEWMODULE activity
 */
class restore_NEWMODULE_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();

        $userinfo = $this->get_setting_value('userinfo'); // are we including userinfo?

        ////////////////////////////////////////////////////////////////////////
        // XML interesting paths - non-user data
        ////////////////////////////////////////////////////////////////////////

        // root element describing NEWMODULE instance
        $oneactivity = new restore_path_element(MOD_NEWMODULE_NAME, '/activity/NEWMODULE');
        $paths[] = $oneactivity;

		

        // End here if no-user data has been selected
        if (!$userinfo) {
            return $this->prepare_activity_structure($paths);
        }

        ////////////////////////////////////////////////////////////////////////
        // XML interesting paths - user data
        ////////////////////////////////////////////////////////////////////////
		//attempts
		 $attempts= new restore_path_element(MOD_NEWMODULE_USERTABLE,
                                            '/activity/NEWMODULE/attempts/attempt');
		$paths[] = $attempts;
		 


        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_NEWMODULE($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        $data->timemodified = $this->apply_date_offset($data->timemodified);
        $data->timecreated = $this->apply_date_offset($data->timecreated);


        // insert the activity record
        $newitemid = $DB->insert_record(MOD_NEWMODULE_TABLE, $data);
        // immediately after inserting "activity" record, call this
        $this->apply_activity_instance($newitemid);
    }

	
	protected function process_NEWMODULE_attempts($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->timecreated = $this->apply_date_offset($data->timecreated);

		
        $data->{MOD_NEWMODULE_NAME . 'id'} = $this->get_new_parentid(MOD_NEWMODULE_NAME);
        $newitemid = $DB->insert_record(MOD_NEWMODULE_USERTABLE, $data);
       $this->set_mapping(MOD_NEWMODULE_USERTABLE, $oldid, $newitemid, false); // Mapping without files
    }
	
    protected function after_execute() {
        // Add module related files, no need to match by itemname (just internally handled context)
        $this->add_related_files(MOD_NEWMODULE_FRANKY, 'intro', null);
    }
}
