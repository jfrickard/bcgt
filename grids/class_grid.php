<?php
/*
 * Moodle Gradetracker V1.0 – This code is copyright of Bedford College and is 
 * supplied for evaluation purposes only. The code may not be used for any 
 * purpose without permission from The Learning Technologies Team, 
 * Bedford College:  moodlegrades@bedford.ac.uk
 * 
 * Author mchaney@bedford.ac.uk
 */

global $COURSE, $CFG, $PAGE, $OUTPUT, $USER, $DB;
require_once('../../../config.php');
require_once('../lib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot.'/blocks/bcgt/lib.php');
$courseID = optional_param('cID', -1, PARAM_INT);
if($courseID != -1)
{
    $context = context_course::instance($courseID);
}
else
{
    $context = context_course::instance($COURSE->id);
}
require_login();
$PAGE->set_context($context);
$qualID = optional_param('qID', -1, PARAM_INT);
$studentID = optional_param('sID', -1, PARAM_INT);
$unitID = optional_param('uID', -1, PARAM_INT);
$clearSession = optional_param('csess', true, PARAM_BOOL);
$qualification = null;
if(!$clearSession)
{
    $sessionQuals = isset($_SESSION['session_stu_quals'])? 
    unserialize(urldecode($_SESSION['session_stu_quals'])) : array(); 
}
else
{
    $sessionQuals = array();
}

//this will be an array of qualID => qual
//does the qual exist already for this session?
if(array_key_exists($qualID, $sessionQuals))
{
    $qualification = $sessionQuals[$qualID];
}
else
{
    $loadParams = new stdClass();
    $loadParams->loadLevel = Qualification::LOADLEVELALL;
    $qualification = Qualification::get_qualification_class_id($qualID, $loadParams);
}

$url = '/blocks/bcgt/forms/class_grid.php';
$PAGE->set_url($url, array());
$PAGE->set_title(get_string('bcgtmydashboard', 'block_bcgt'));
$PAGE->set_heading(get_string('bcgtmydashboard', 'block_bcgt'));
$PAGE->set_pagelayout('login');
$PAGE->add_body_class(get_string('bcgtmydashboard', 'block_bcgt'));
$PAGE->navbar->add(get_string('pluginname', 'block_bcgt'),$CFG->wwwroot.'/blocks/bcgt/forms/my_dashboard.php','title');
$jsModule = array(
    'name'     => 'block_bcgt',
    'fullpath' => '/blocks/bcgt/js/block_bcgt.js',
    'requires' => array('base', 'io', 'node', 'json', 'event', 'button')
);
$PAGE->requires->js_init_call('M.block_bcgt.initgridclass', null, true, $jsModule);
require_once($CFG->dirroot.'/blocks/bcgt/lib.php');
load_javascript();
$out = $OUTPUT->header();
    $out .= '<form id="classGridForm" method="POST" name="classGridForm" action="class_grid.php?">';			
    $out .= '<input type="hidden" name="cID" value="'.$courseID.'"/>';
    
    // Menu
    $out .= '<div class="bcgtGridMenu">';
    if(has_capability('block/bcgt:viewclassgrids', $context))
    {
        $dropDowns = "yes";
        //Drop down of other quals
        
        if(has_capability('block/bcgt:viewallgrids', context_system::instance()))
        {
            $qualifications = search_qualification(-1, -1, -1, '', 
                -1, null, -1, true, true, null); 
        }
        else
        {
            $qualifications = get_users_quals($USER->id);
        }
        if($qualifications)
        {
            $out .= '<div class="bcgtQualChange">';
            $out .= '<label for="qualChange">Change Qualification to : </label>';
            $out .= '<select id="qualChange" name="qID"><option value=""></option>';
            foreach($qualifications AS $qual)
            {
                $selected = '';
                if($qualID == $qual->id)
                {
                    $selected = "selected";
                }
                $out .= '<option '.$selected.' value="'.$qual->id.'">'.
                        bcgt_get_qualification_display_name($qual).'</option>';
            }
            $out .= '</select>';
            $out .= '</div>'; //bcgtQualChange
        }
        else
        {
            $dropDowns = "no";
            $out .= '<input type="hidden" id="qID" name="qID" value="'.$qualID.'"/>';
        }
    }
    else
    {
        $dropDowns = "no";
        $out .= '<input type="hidden" id="sID" name="sID" value="'.$studentID.'"/>';
        $out .= '<input type="hidden" id="qID" name="qID" value="'.$qualID.'"/>';
    }
    $out .= '<input type="hidden" id="selects" name="selects" value="'.$dropDowns.'"/>'; 
    $out .= '<input type="hidden" id="user" name="user" value="'.$USER->id.'"/>';
    
    $out .= get_grid_menu($studentID, $unitID, $qualID);
    $out .= '</div>';
    
    $heading = get_string('trackinggrid','block_bcgt');
    $heading .= " - ".$qualification->get_display_name()."";
    $out .= html_writer::tag('h2', $heading, 
        array('class'=>'formheading'));
    
    $out .= html_writer::start_tag('div', array('class'=>'bcgt_grid_outer', 
    'id'=>'classGridOuter'));
    //at this point we load it up into the session
    $out .= $qualification->display_subject_grid();
    
    $sessionQuals[$qualID] = $qualification;
    $_SESSION['session_stu_quals'] = urlencode(serialize($sessionQuals));

    $out .= html_writer::end_tag('div');
    $out .= '</form>';			
$out .= $OUTPUT->footer();
echo $out;

?>