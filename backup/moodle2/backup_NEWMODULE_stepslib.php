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
 * Defines all the backup steps that will be used by {@link backup_@@newmodule@@_activity_task}
 *
 * @package     mod_@@newmodule@@
 * @category    backup
 * @copyright   COPYRIGHTNOTICE
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/@@newmodule@@/lib.php');

/**
 * Defines the complete webquest structure for backup, with file and id annotations
 *
 */
class backup_@@newmodule@@_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure of the @@newmodule@@ element inside the webquest.xml file
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // are we including userinfo?
        $userinfo = $this->get_setting_value('userinfo');

        ////////////////////////////////////////////////////////////////////////
        // XML nodes declaration - non-user data
        ////////////////////////////////////////////////////////////////////////

        // root element describing @@newmodule@@ instance
        $oneactivity = new backup_nested_element(MOD_NEWMODULE_MODNAME, array('id'), array(
            'course','name','intro','introformat','someinstancesetting','grade','gradeoptions','maxattempts','mingrade',
			'timecreated','timemodified'
			));
		
		//attempts
        $attempts = new backup_nested_element('attempts');
        $attempt = new backup_nested_element('attempt', array('id'),array(
			MOD_NEWMODULE_MODNAME ."id","course","userid","status","sessionscore","timecreated","timemodified"
		));

		
		// Build the tree.
        $oneactivity->add_child($attempts);
        $attempts->add_child($attempt);
		


        // Define sources.
        $oneactivity->set_source_table(MOD_NEWMODULE_TABLE, array('id' => backup::VAR_ACTIVITYID));

        //sources if including user info
        if ($userinfo) {
			$attempt->set_source_table(MOD_NEWMODULE_USERTABLE,
											array(MOD_NEWMODULE_MODNAME . 'id' => backup::VAR_PARENTID));
        }

        // Define id annotations.
        $attempt->annotate_ids('user', 'userid');


        // Define file annotations.
        // intro file area has 0 itemid.
        $oneactivity->annotate_files(MOD_NEWMODULE_FRANKY, 'intro', null);

        // Return the root element (choice), wrapped into standard activity structure.
        return $this->prepare_activity_structure($oneactivity);
		

    }
}
