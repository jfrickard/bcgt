<?php


/*
 * Moodle Gradetracker V1.0 – This code is copyright of Bedford College and is 
 * supplied for evaluation purposes only. The code may not be used for any 
 * purpose without permission from The Learning Technologies Team, 
 * Bedford College:  moodlegrades@bedford.ac.uk
 * 
 * Author mchaney@bedford.ac.uk
 */
global $COURSE, $CFG, $PAGE, $OUTPUT, $USER, $DB;;
require_once('../../../config.php');
require_once('../lib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

$cID = optional_param('cID', -1, PARAM_INT);
if($cID != -1)
{
    $context = context_course::instance($cID);
}
else
{
    $context = context_course::instance($COURSE->id);
}
require_login();

$PAGE->set_context($context);
require_capability('block/bcgt:viewclassgrids', $context);
$grid = optional_param('g', 's', PARAM_TEXT);
$qualID = optional_param('qID', -1, PARAM_INT);
$aQualID = optional_param('aqID', -1, PARAM_INT);
$courseID = optional_param('courseID', -1, PARAM_INT);
$aCourseID = optional_param('acourseID', -1, PARAM_INT);
$studentID = optional_param('students', -1, PARAM_INT);
$assID = optional_param('assessments', -1, PARAM_INT);
$unitID = optional_param('units', -1, PARAM_INT);
$search = optional_param('search', '', PARAM_TEXT);
$initialLoad = optional_param('il', true, PARAM_BOOL);
if($cID != 1 && $courseID == -1 && $initialLoad)
{
    $courseID = $cID;
}
$viewAll = false;
$qualExcludes = array();
if($grid == 'u')
{
    $qualExcludes = array('ALevel');
}
if($grid == 'c')
{
    $qualExcludes = array('BTEC');
}
if($studentID != -1 && $qualID != -1)
{
    //then a student has been selected in the drop down. 
    //The user just wants to look at that student, so lets go to their simple grid. 
//    redirect($CFG->wwwroot.'/blocks/bcgt/grids/student_grid.php?sID='.$studentID.'g=s&cID='.$courseID);
}
if(has_capability('block/bcgt:viewallgrids', context_system::instance()))
{
    $viewAll = true;
    $onCourse = null;
    if($courseID != -1)
    {
        $onCourse = true;
    }
    $allQuals = search_qualification(-1, -1, -1, '', 
        -1, null, -1, $onCourse, true, $qualExcludes); 
    $allCourses = bcgt_get_courses_with_quals(-1, $qualExcludes);
}
//else
//{
$teacher = $DB->get_record_select('role', 'shortname = ?', array('editingteacher'));
$userQualRole = $DB->get_record_select('role', 'shortname = ?', array('teacher'));
$quals = get_users_quals($USER->id, array($userQualRole->id, $teacher->id), '', -1, -1, $qualExcludes);
$courses = bcgt_get_users_courses($USER->id, $teacher->id, true, -1, $qualExcludes);
//}
switch($grid)
{
   case 's':
       $string = 'gridselectstudent';
       break;
   case 'u':
       $string = 'gridselectunit';
       break;
   case 'c':
       $string = 'gridselectclass';
       break;
   case 'a':
       $string = 'gridselectassessment';
       break;
}


$url = '/blocks/bcgt/forms/grid_select.php';
$PAGE->set_url($url, array());
$PAGE->set_title(get_string($string, 'block_bcgt'));
$PAGE->set_heading(get_string($string, 'block_bcgt'));
$PAGE->set_pagelayout('login');
$PAGE->add_body_class(get_string('gridselect', 'block_bcgt'));
$PAGE->navbar->add(get_string('pluginname', 'block_bcgt'),'','title');
$PAGE->navbar->add(get_string($string, 'block_bcgt'),'','title');

$jsModule = array(
    'name'     => 'block_bcgt',
    'fullpath' => '/blocks/bcgt/js/block_bcgt.js',
    'requires' => array('base', 'io', 'node', 'json', 'event', 'button')
);
$PAGE->requires->js_init_call('M.block_bcgt.initgridselect', null, true, $jsModule);
require_once($CFG->dirroot.'/blocks/bcgt/lib.php');
$out = $OUTPUT->header();
$out .= load_javascript(true, true);
$out .= html_writer::tag('h2', get_string($string,'block_bcgt').
        '', 
        array('class'=>'formheading'));
        //needs to check available capibilities
$out .= '<div class="tabs"><div class="tabtree">';
$out .= '<ul class="tabrow0">';
$out .= '<li>'.
        '<a href="'.$CFG->wwwroot.'/blocks/bcgt/forms/grid_select.php?g=s&cID='.$cID.'">'.
        '<span>'.get_string('byStudent', 'block_bcgt').'</span></a></li>';
$out .= '<li>'.
        '<a href="'.$CFG->wwwroot.'/blocks/bcgt/forms/grid_select.php?g=u&cID='.$cID.'">'.
        '<span>'.get_string('byUnit', 'block_bcgt').'</span></a></li>';
$out .= '<li>'.
        '<a href="'.$CFG->wwwroot.'/blocks/bcgt/forms/grid_select.php?g=c&cID='.$cID.'">'.
        '<span>'.get_string('byClass', 'block_bcgt').'</span></a></li>';
if(get_config('bcgt','alevelusefa'))
{
    $out .= '<li>'.
        '<a href="'.$CFG->wwwroot.'/blocks/bcgt/forms/grid_select.php?g=a&cID='.$cID.'">'.
        '<span>'.get_string('byassessment', 'block_bcgt').'</span></a></li>';
}
$out .= '</ul>';
$out .= '</div></div>';
$out .= html_writer::start_tag('div', array('class'=>'bcgt_admin_controls', 
    'id'=>'editCourseQual'));
$out .= '<p>'.get_string('disabledoptiondescgridselect', 'block_bcgt').'</p>';
$out .= '<form name="gridselect" action="grid_select.php" method="POST" id="gridselect">';
$out .= '<input type="hidden" id="cID" name="cID" value="'.$cID.'"/>';
$out .= '<input type="hidden" name="g" value="'.$grid.'"/>';
$out .= '<input type="hidden" name="il" value="false"/>';
$out .= '<div class="inputContainer"><div class="inputLeft">'.
            '<label for="type">'.get_string('myquals', 'block_bcgt').'</label></div>';
    $out .= '<div class="inputRight"><select name="qID" id="qual"><option value="-1">Please select one</option>';
if($quals)
{    
    foreach($quals AS $qual)
    {
        $disabled = '';
        //is this qual actuall on a course?
        $onCourse = $DB->get_records_sql('SELECT * FROM {block_bcgt_course_qual} WHERE bcgtqualificationid = ?', array($qual->id));
        if(!$onCourse)
        {
            $disabled = 'disabled';
        }
        
        $class = '';
        $hasStudents = $DB->get_records_sql('SELECT userqual.id FROM {block_bcgt_user_qual} userqual 
            JOIN {role} role ON role.id = userqual.roleid WHERE bcgtqualificationid = ? AND role.shortname = ?', 
                array($qual->id, 'student'));
        if(!$hasStudents)
        {
            $class = 'noStudents';
        }
        if(count($quals) == 1)
        {
            $qualID = $qual->id;
        }
        $selected = '';
        if(count($quals) == 1 || ($qualID != -1 && $qualID == $qual->id))
        {
            $selected = 'selected';
        }
        $out .= '<option class="'.$class.'" '.$selected.' value="'.$qual->id.' '.$disabled.'">'.
                bcgt_get_qualification_display_name($qual, true, ' ').'</option>';
    }
}
$out .= '</select>';
$out .= '</div></div>';
if(has_capability('block/bcgt:viewallgrids', context_system::instance()) && $allQuals)
{    
    $out .= '<div class="inputContainer"><div class="inputLeft">'.
            '<label for="type">'.get_string('allquals', 'block_bcgt').'</label></div>';
    $out .= '<div class="inputRight"><select name="aqID" id="aqual"><option value="-1">Please select one</option>';
    foreach($allQuals AS $qual)
    {
        if(count($allQuals) == 1)
        {
            $qualID = $qual->id;
        }
        $selected = '';
        if(count($allQuals) == 1 || ($aQualID != -1 && $aQualID == $qual->id))
        {
            $selected = 'selected';
        }
        $out .= '<option '.$selected.' value="'.$qual->id.'">'.
                bcgt_get_qualification_display_name($qual, true, ' ').'</option>';
    }
    $out .= '</select>';
    $out .= '</div></div>';
}


$out .= '<input id="grid" type="hidden" name="grid" value="'.$grid.'"/>';
$out .= '<div class="inputContainer"><div class="inputLeft">'.
            '<label for="course">'.get_string('mycourse', 'block_bcgt').'</label></div>';
    $out .= '<div class="inputRight"><select name="courseID" id="course"><option value="-1">Please select one</option>';
if($courses)
{    
    foreach($courses AS $course)
    {
//        if(count($courses) == 1)
//        {
//            $courseID = $course->id;
//        }
        $selected = '';
        if(count($courses) == 1 || ($courseID != -1 && $courseID == $course->id))
        {
            $selected = 'selected';
        }
        $out .= '<option '.$selected.' value="'.$course->id.'">'.
                $course->shortname.':'.$course->fullname.'</option>';
    }
}
$out .= '</select>';
$out .= '</div></div>';
if(has_capability('block/bcgt:viewallgrids', context_system::instance()) && $allCourses)
{    
    $out .= '<div class="inputContainer"><div class="inputLeft">'.
            '<label for="acourseID">'.get_string('allcourse', 'block_bcgt').'</label></div>';
    $out .= '<div class="inputRight"><select name="acourseID" id="acourse"><option value="-1">Please select one</option>';
    foreach($allCourses AS $course)
    {
//        if(count($allCourses) == 1)
//        {
//            $courseID = $course->id;
//        }
        $selected = '';
        if(count($allCourses) == 1 || ($aCourseID != -1 && $aCourseID == $course->id))
        {
            $selected = 'selected';
        }
        $out .= '<option '.$selected.' value="'.$course->id.'">'.
                $course->shortname.':'.$course->fullname.'</option>';
    }
    $out .= '</select>';
    $out .= '</div></div>';
}
if($grid == 's')
{
    $searchString = 'searchstudent';
    //then have a student or qual searchable
    //drop down of all of their students
//    if(!$viewAll)
//    {
        $stuRole = $DB->get_record_select('role', 'shortname = ?', array('student'));
        $students = bcgt_get_users_users($USER->id, array($userQualRole->id, $teacher->id), $stuRole->id, $search);
        if($students)
        {
            $out .= '<div class="inputContainer"><div class="inputLeft">'.
            '<label for="students">'.get_string('mystudents', 'block_bcgt').'</label></div>';
            $out .= '<div class="inputRight"><select name="students" id="studentID"><option value="-1">Please select one</option>'; 
            foreach($students AS $student)
            {
                $selected = '';
                if($student->id == $studentID)
                {
                    $selected = 'selected';
                }
                $out .= '<option '.$selected.' value="'.$student->id.'">'.
                        $student->username .' : '.$student->firstname.' '.$student->lastname.'</option>';
            }
            $out .= '</select>';
            $out .= '</div></div>';
        }
//    }
}
elseif($grid == 'u')
{
    $searchString = 'searchunit';
    //then have a unit or qual searchable
    //drop down of all of theur units
    //then have a student or qual searchable
    //drop down of all of their students
//    if(!$viewAll)
//    {
        $teacherRole = $DB->get_record_select('role', 'shortname = ?', array('teacher'));
        $units = bcgt_get_users_units($USER->id, $teacherRole->id, $search);
        if($units)
        {
            $out .= '<div class="inputContainer"><div class="inputLeft">'.
            '<label for="units">'.get_string('myunits', 'block_bcgt').'</label></div>';
            $out .= '<div class="inputRight"><select name="units" id="unitID"><option value="-1">Please select one</option>'; 
            foreach($units AS $unit)
            {
                $selected = '';
                if($unit->id == $unitID)
                {
                    $selected = 'selected';
                }
                $out .= '<option '.$selected.' value="'.$unit->id.'">'.
                        $unit->uniqueid .' : '.$unit->name.'</option>';
            }
            $out .= '</select>';
            $out .= '</div></div>';
        }
//    }
}
elseif($grid == 'a')
{
    $searchString = 'searchass';
//    if(!$viewAll)
//    {
        $userQualRole = $DB->get_record_select('role', 'shortname = ?', array('teacher'));
        $assessments = bcgt_get_users_assessments($USER->id, $userQualRole->id, $search);
        if($assessments)
        {
            $out .= '<div class="inputContainer"><div class="inputLeft">'.
            '<label for="students">'.get_string('myassessments', 'block_bcgt').'</label></div>';
            $out .= '<div class="inputRight"><select name="assessments" id="assID"><option value="-1">Please select one</option>'; 
            foreach($assessments AS $ass)
            {
                $selected = '';
                if($ass->id == $assID)
                {
                    $selected = 'selected';
                }
                $out .= '<option '.$selected.' value="'.$ass->id.'">'.
                        $ass->targetdate .' : '.$ass->name.'</option>';
            }
            $out .= '</select>';
            $out .= '</div></div>';
        }
//    }
    //then have a assessment or qual that is seachable
    //drop down of all of their assessments
}
elseif($grid == 'c')
{
    $searchString = 'searchclass';
    //then have a qual that is searchable.
}
$out .= '<div class="inputContainer"><div class="inputLeft">'.
'<label for="search">'.get_string($searchString, 'block_bcgt').'</label></div>';
$out .= '<div class="inputRight"><input type="text" name="search" value="'.$search.'"/>';
$out .= '</div></div>';
$out .= '<div class="inputContainer"><div class="inputLeft">'.
'<input type="submit" name="searchsubmit" value="'.get_string('search', 'block_bcgt').'"/></div>';
$out .= '<div class="inputRight">';
$out .= '</div></div>';
$out .= '<p>'.get_string('griddisabledlinksdesc','block_bcgt').'</p>';
if($grid == 's')
{
    
    if($qualID != -1)
    {
        //we have the qualification that has been selected in the drop downn
        $out .= bcgt_display_qual_grid_select($qualID, $cID, $search);
    }
    elseif($aQualID != -1)
    {
        //we have the qualification that has been selected in the drop downn
        $out .= bcgt_display_qual_grid_select($aQualID, $cID, $search);
    }
    
    elseif($courseID != -1 || $aCourseID != -1)
    {
        //we have a course ID and we only have once course to show
//        if(count($courses) == 1)
//        {
//            $course = end($courses);
//            $courseID = $course->id;
//        }
        if($courseID != -1)
        {
            //then we need to get all of the quals that are on this course. 
            $quals = bcgt_get_course_quals($courseID, -1, $qualID, $qualExcludes);
        }
        elseif($aCourseID != -1)
        {
            //then we need to get all of the quals that are on this course. 
            $quals = bcgt_get_course_quals($aCourseID, -1, $qualID, $qualExcludes);
        }
        if($quals)
        {
            foreach($quals AS $qual)
            {
                $out .= bcgt_display_qual_grid_select($qual->id, $cID, $search);
            }
        }
        
    }
    elseif($studentID != -1)
    {
        //then we need to  display the options for just one student
        $out .= bcgt_display_student_grid_select($search, $USER->id, $studentID);
    }
    elseif($viewAll && $search != '')
    {
        //so we are an admin looking for any students
        //then we can find any student(s)
        $out .= bcgt_display_student_grid_select($search);
    }
    elseif(!$viewAll && $search != '')
    {
        //so we are not an admin, we have just entered a search
        //so lets search for the students out of the ones I can see. 
        $out .= bcgt_display_student_grid_select($search, $USER->id);
    }
}
elseif($grid == 'u')
{
    if($courseID != -1 || $aCourseID != -1)
    {
        //then we need to get all of the quals that are on this course.
        if($courseID != -1)
        {
            $quals = bcgt_get_course_quals($courseID, -1, $qualID, $qualExcludes);
        }
        elseif($aCourseID != -1)
        {
            $quals = bcgt_get_course_quals($aCourseID, -1, $qualID, $qualExcludes);
        }
        if($quals)
        {
            foreach($quals AS $qual)
            {
                $out .= '<h3 class="bcgtUnitQualHeading">'.bcgt_get_qualification_display_name($qual).'</h3>';
                $out .= bcgt_display_unit_grid_select($qual->id, $cID, $search);
            }
        }
    }
    elseif($qualID != -1)
    {
        $out .= bcgt_display_unit_grid_select($qualID, $cID, $search);
    }
    elseif($aQualID != -1)
    {
        //we have the qualification that has been selected in the drop downn
        $out .= bcgt_display_unit_grid_select($aQualID, $cID, $search);
    }
    elseif($unitID != -1)
    {
        //then display for just the one unit and all of the possible quals
        //then we can find any unit of those that we can see
        $out .= bcgt_display_unit_grid_select_search($search, $qualExcludes, $USER->id, $unitID);
    }
    elseif($viewAll && $search != '')
    {
        //then we can find any units in the entire system (that belong to quals that are 
        //on a course)
        $out .= bcgt_display_unit_grid_select_search($search, $qualExcludes);
    }
    elseif(!$viewAll && $search != '')
    {
        //then we can find any unit of those that we can see
        $out .= bcgt_display_unit_grid_select_search($search, $qualExcludes, $USER->id);
    }
}
elseif($grid == 'c')
{
    //if we are here we have one course and potentially multiple quals.
    //if a qual was selected then it will go straight to the grid
    if($courseID != -1 || $aCourseID != -1)
    {
        if($courseID != -1)
        {
            $out .= bcgt_display_class_grid_select($courseID, $cID, $qualExcludes, $search);
        }
        else
        {
            $out .= bcgt_display_class_grid_select($aCourseID, $cID, $qualExcludes, $search);
        }
    }
    elseif($viewAll && $search != '')
    {
        //then we can find any student(s)
        $out .= bcgt_display_class_grid_select_search($cID, $search, $qualExcludes);
    }
    else
    {
        $out .= bcgt_display_class_grid_select_search($cID, $search, $qualExcludes, $USER->id); 
    }
}
elseif($grid == 'a')
{
    //if we are here we have one course and potentially multiple quals
    //if a qual was selected then it will go straight to the grid
    //this if we are here we have a qual
    //so get all of the quals that we have on that course
    if($courseID != -1 || $aCourseID != -1)
    {
        if($courseID != -1)
        {
            $out .= bcgt_display_assessment_grid_select($courseID);
        }
        else
        {
            $out .= bcgt_display_assessment_grid_select($aCourseID);
        }
    }
    elseif($assID != -1)
    {
        //then display for a specific assignment and all of the quals
        //it could be on. 
        $out .= bcgt_display_assessment_grid_select_search($search, $USER->id, $assID);
    }
    elseif($viewAll && $search != '')
    {
        //then we can find any assessments
        $out .= bcgt_display_assessment_grid_select_search($search);
    }
    elseif(!$viewAll && $search != '')
    {
        //then we can find any student(s)
        $out .= bcgt_display_assessment_grid_select_search($search, $USER->id);
    }
}
$out .= '</form>';
$out .= html_writer::end_tag('div');//end main column
$out .= $OUTPUT->footer();

echo $out;
?>
