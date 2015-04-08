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
* Sets up the tabs at the top of the module view page　for teachers.
*
* This file was adapted from the mod/lesson/tabs.php
*
 * @package mod_NEWMODULE
 * @copyright  2014 Justin Hunt  {@link http://poodll.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or late
*/

defined('MOODLE_INTERNAL') || die();

/// This file to be included so we can assume config.php has already been included.
global $DB;
if (empty($moduleinstance)) {
    print_error('cannotcallscript');
}
if (!isset($currenttab)) {
    $currenttab = '';
}
if (!isset($cm)) {
    $cm = get_coursemodule_from_instance(MOD_NEWMODULE_MODNAME, $moduleinstance->id);
    $context = context_module::instance($cm->id);
}
if (!isset($course)) {
    $course = $DB->get_record('course', array('id' => $moduleinstance->course));
}

$tabs = $row = $inactive = $activated = array();


$row[] = new tabobject('view', "$CFG->wwwroot/mod/NEWMODULE/view.php?id=$cm->id", get_string('view', MOD_NEWMODULE_LANG), get_string('preview', MOD_NEWMODULE_LANG, format_string($moduleinstance->name)));
$row[] = new tabobject('reports', "$CFG->wwwroot/mod/NEWMODULE/reports.php?id=$cm->id", get_string('reports', MOD_NEWMODULE_LANG), get_string('viewreports', MOD_NEWMODULE_LANG));

$tabs[] = $row;

print_tabs($tabs, $currenttab, $inactive, $activated);
