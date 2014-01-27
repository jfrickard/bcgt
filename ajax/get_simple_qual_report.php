<?php
require_once('../../../config.php');
global $COURSE, $CFG, $PAGE, $OUTPUT, $USER, $DB;
require_once('../lib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
$context = context_course::instance($COURSE->id);
require_login();
$PAGE->set_context($context);

$qualID = required_param('qID', PARAM_INT);
$edit = optional_param('edit', false, PARAM_BOOL);
$tab = optional_param('tab', 's', PARAM_TEXT);
$uFilter = optional_param('ufilter', 'all', PARAM_TEXT);
$tFilter = optional_param('tfilter', 'all', PARAM_TEXT);
$sort = optional_param('sort', '', PARAM_TEXT);

$filter = array();
$filter['units'] = $uFilter;
$filter['target'] = $tFilter;
$sortArray = explode(",", $sort);
$courseID = optional_param('cID', -1, PARAM_INT);
$userID = $USER->id;
$qualification = Qualification::get_qualification_class_id($qualID);
$retval = '';
if($qualification)
{
    $retval = $qualification->get_simple_qual_report($userID, $tab, $edit, $courseID, $filter, $sortArray);
}

$output = array(
		"qualid" => $qualID,
		"retval" => $retval,
	);
	echo json_encode( $output );

?>
