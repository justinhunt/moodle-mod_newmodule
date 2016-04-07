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
 * Prints a particular instance of @@newmodule@@
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_@@newmodule@@
 * @copyright  COPYRIGHTNOTICE
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');


$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // @@newmodule@@ instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('@@newmodule@@', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance  = $DB->get_record('@@newmodule@@', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $moduleinstance  = $DB->get_record('@@newmodule@@', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('@@newmodule@@', $moduleinstance->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('missingidandcmid',MOD_NEWMODULE_LANG));
}

$PAGE->set_url('/mod/@@newmodule@@/view.php', array('id' => $cm->id));
require_login($course, true, $cm);
$modulecontext = context_module::instance($cm->id);

//Diverge logging logic at Moodle 2.7
if($CFG->version<2014051200){
	add_to_log($course->id, '@@newmodule@@', 'view', "view.php?id={$cm->id}", $moduleinstance->name, $cm->id);
}else{
	// Trigger module viewed event.
	$event = \mod_@@newmodule@@\event\course_module_viewed::create(array(
	   'objectid' => $moduleinstance->id,
	   'context' => $modulecontext
	));
	$event->add_record_snapshot('course_modules', $cm);
	$event->add_record_snapshot('course', $course);
	$event->add_record_snapshot('@@newmodule@@', $moduleinstance);
	$event->trigger();
} 

//if we got this far, we can consider the activity "viewed"
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

//are we a teacher or a student?
$mode= "view";

/// Set up the page header
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);
$PAGE->set_pagelayout('course');

	//Get an admin settings 
	$config = get_config(MOD_NEWMODULE_FRANKY);
  	$someadminsetting = $config->someadminsetting;

	//Get an instance setting
	$someinstancesetting = $moduleinstance->someinstancesetting;


//get our javascript all ready to go
//We can omit $jsmodule, but its nice to have it here, 
//if for example we need to include some funky YUI stuff
$jsmodule = array(
	'name'     => 'mod_@@newmodule@@',
	'fullpath' => '/mod/@@newmodule@@/module.js',
	'requires' => array()
);
//here we set up any info we need to pass into javascript
$opts =Array();
$opts['someinstancesetting'] = $someinstancesetting;


//this inits the M.mod_@@newmodule@@ thingy, after the page has loaded.
$PAGE->requires->js_init_call('M.mod_@@newmodule@@.helper.init', array($opts),false,$jsmodule);

//this loads any external JS libraries we need to call
//$PAGE->requires->js("/mod/@@newmodule@@/js/somejs.js");
//$PAGE->requires->js(new moodle_url('http://www.somewhere.com/some.js'),true);

//This puts all our display logic into the renderer.php file in this plugin
//theme developers can override classes there, so it makes it customizable for others
//to do it this way.
$renderer = $PAGE->get_renderer('mod_@@newmodule@@');

//From here we actually display the page.
//this is core renderer stuff


//if we are teacher we see tabs. If student we just see the quiz
if(has_capability('mod/@@newmodule@@:preview',$modulecontext)){
	echo $renderer->header($moduleinstance, $cm, $mode, null, get_string('view', MOD_NEWMODULE_LANG));
}else{
	echo $renderer->notabsheader();
}

echo $renderer->show_intro($moduleinstance,$cm);

//if we have too many attempts, lets report that.
if($moduleinstance->maxattempts > 0){
	$attempts =  $DB->get_records(MOD_NEWMODULE_USERTABLE,array('userid'=>$USER->id, MOD_NEWMODULE_MODNAME.'id'=>$moduleinstance->id));
	if($attempts && count($attempts)<$moduleinstance->maxattempts){
		echo get_string("exceededattempts",MOD_NEWMODULE_LANG,$moduleinstance->maxattempts);
	}
}

//This is specfic to our renderer
echo $renderer->show_something($someadminsetting);
echo $renderer->show_something($someinstancesetting);

// Finish the page
echo $renderer->footer();
