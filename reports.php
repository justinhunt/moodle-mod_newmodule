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
 * Reports for @@newmodule@@
 *
 *
 * @package    mod_@@newmodule@@
 * @copyright  COPYRIGHTNOTICE
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/reportclasses.php');


$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // englishcentral instance ID - it should be named as the first character of the module
$format = optional_param('format', 'html', PARAM_TEXT); //export format csv or html
$showreport = optional_param('report', 'menu', PARAM_TEXT); // report type
$userid = optional_param('userid', 0, PARAM_INT); // userid
$attemptid = optional_param('attemptid', 0, PARAM_INT); // attemptid

//paging details
$paging = new stdClass();
$paging->perpage = optional_param('perpage',40, PARAM_INT);
$paging->pageno = optional_param('pageno',0, PARAM_INT);
$paging->sort  = optional_param('sort','iddsc', PARAM_TEXT);


if ($id) {
    $cm         = get_coursemodule_from_id(MOD_NEWMODULE_MODNAME, $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance  = $DB->get_record(MOD_NEWMODULE_TABLE, array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $moduleinstance  = $DB->get_record(MOD_NEWMODULE_TABLE, array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance(MOD_NEWMODULE_TABLE, $moduleinstance->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

if($showreport=='menu'){
	$PAGE->set_url(MOD_NEWMODULE_URL . '/reports.php', array('id' => $cm->id));
}else{
	$PAGE->set_url(MOD_NEWMODULE_URL . '/reports.php', 
		array('id' => $cm->id,'format'=>$format,'report'=>$showreport,'userid'=>$userid,'attemptid'=>$attemptid));
}
require_login($course, true, $cm);
$modulecontext = context_module::instance($cm->id);

//Diverge logging logic at Moodle 2.7
if($CFG->version<2014051200){
	add_to_log($course->id, MOD_NEWMODULE_MODNAME, 'reports', "reports.php?id={$cm->id}", $moduleinstance->name, $cm->id);
}else{
	// Trigger module viewed event.
	$event = \mod_@@newmodule@@\event\course_module_viewed::create(array(
	   'objectid' => $moduleinstance->id,
	   'context' => $modulecontext
	));
	$event->add_record_snapshot('course_modules', $cm);
	$event->add_record_snapshot('course', $course);
	$event->add_record_snapshot(MOD_NEWMODULE_MODNAME, $moduleinstance);
	$event->trigger();
} 


/// Set up the page header
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);
$PAGE->set_pagelayout('course');

	//Get an admin settings 
	$config = get_config(MOD_NEWMODULE_FRANKY);


//This puts all our display logic into the renderer.php files in this plugin
$renderer = $PAGE->get_renderer(MOD_NEWMODULE_FRANKY);
$reportrenderer = $PAGE->get_renderer(MOD_NEWMODULE_FRANKY,'report');

//From here we actually display the page.
//this is core renderer stuff
$mode = "reports";
$extraheader="";
switch ($showreport){

	//not a true report, separate implementation in renderer
	case 'menu':
		echo $renderer->header($moduleinstance, $cm, $mode, null, get_string('reports', MOD_NEWMODULE_LANG));
		echo $reportrenderer->render_reportmenu($moduleinstance,$cm);
		// Finish the page
		echo $renderer->footer();
		return;

	case 'basic':
		$report = new mod_@@newmodule@@_basic_report();
		$formdata = new stdClass();
		break;

		
		
	default:
		echo $renderer->header($moduleinstance, $cm, $mode, null, get_string('reports', MOD_NEWMODULE_LANG));
		echo "unknown report type.";
		echo $renderer->footer();
		return;
}

/*
1) load the class
2) call report->process_raw_data
3) call $rows=report->fetch_formatted_records($withlinks=true(html) false(print/excel))
5) call $reportrenderer->render_section_html($sectiontitle, $report->name, $report->get_head, $rows, $report->fields);
*/

$report->process_raw_data($formdata);
$reportheading = $report->fetch_formatted_heading();

switch($format){
	case 'csv':
		$reportrows = $report->fetch_formatted_rows(false);
		$reportrenderer->render_section_csv($reportheading, $report->fetch_name(), $report->fetch_head(), $reportrows, $report->fetch_fields());
		exit;
	default:
		
		$reportrows = $report->fetch_formatted_rows(true);
		$allrowscount = $report->fetch_all_rows_count();
		$pagingbar = $reportrenderer->show_paging_bar($allrowscount, $paging,$PAGE->url);
		echo $renderer->header($moduleinstance, $cm, $mode, null, get_string('reports', MOD_NEWMODULE_LANG));
		echo $extraheader;
		echo $pagingbar;
		echo $reportrenderer->render_section_html($reportheading, $report->fetch_name(), $report->fetch_head(), $reportrows, $report->fetch_fields());
		echo $pagingbar;
		echo $reportrenderer->show_reports_footer($moduleinstance,$cm,$formdata,$showreport);
		echo $renderer->footer();
}