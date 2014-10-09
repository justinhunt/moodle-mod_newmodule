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


defined('MOODLE_INTERNAL') || die();

/**
 * A custom renderer class that extends the plugin_renderer_base.
 *
 * @package mod_NEWMODULE
 * @copyright COPYRIGHTNOTICE
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_NEWMODULE_renderer extends plugin_renderer_base {

    /**
     *
     */
    public function show_something($showtext) {
		$ret = $this->output->box_start();
		$ret .= $this->output->heading($showtext, 4, 'main');
		$ret .= $this->output->box_end();
        return $ret;
    }

	 /**
     *
     */
	public function show_intro($NEWMODULE,$cm){
		$ret = "";
		if (trim(strip_tags($NEWMODULE->intro))) {
			echo $this->output->box_start('mod_introbox');
			echo format_module_intro('NEWMODULE', $NEWMODULE, $cm->id);
			echo $this->output->box_end();
		}
	}
  
}

