<?php
/*
 * Moodle Gradetracker V1.0 – This code is copyright of Bedford College and is 
 * supplied for evaluation purposes only. The code may not be used for any 
 * purpose without permission from The Learning Technologies Team, 
 * Bedford College:  moodlegrades@bedford.ac.uk
 * 
 * Author mchaney@bedford.ac.uk
 */

require_once('../../../config.php');
global $CFG;
require_once($CFG->dirroot.'/blocks/bcgt/lib.php');

abstract class DashTab { 
    
    const DASHTAB1 = 'dash';
    const DASHTAB2 = 'track';
    const DASHTAB3 = 'course';
    const DASHTAB4 = 'stu';
    const DASHTAB5 = 'team';
    const DASHTAB6 = 'unit';
    const DASHTAB7 = 'rep';
    const DASHTAB8 = 'ass';
    const DASHTAB9 = 'adm';
    const DASHTAB10 = 'hel';
    const DASHTAB11 = 'feed';
    const DASHTAB12 = 'mess';
    
    function DashTab ()
    {
        
    }
    
    public static function bcgt_get_dashboard_tab_title($tab)
    {
        switch($tab)
        {
            case(DashTab::DASHTAB1):
            case(DashTab::DASHTAB2):
            case(DashTab::DASHTAB3):
            case(DashTab::DASHTAB4):
            case(DashTab::DASHTAB5):
            case(DashTab::DASHTAB6):
            case(DashTab::DASHTAB7):
            case(DashTab::DASHTAB8):
            case(DashTab::DASHTAB9):
            case(DashTab::DASHTAB10):
            case(DashTab::DASHTAB11):
            case(DashTab::DASHTAB12):
                return get_string('dashtab'.$tab, 'block_bcgt');
                break;
            default:
                return DashTab::get_plugin_title($tab);
                break;
        }
        
    }
    
    public static function bcgt_get_dashboard_tabs($tab){
        $retval = "";
        $retval .= DashTab::bcgt_core_get_core_dashboard_tabs($tab);
        $retval .= DashTab::bcgt_get_plugin_tabs($tab);
        return $retval;
    }
    //Dashboard stuff:
    public static function bcgt_core_get_core_dashboard_tabs($tab){
        //this will be driven by capibilities and who is logged in
        $retval = "";
        //get context
        global $COURSE;
        $courseContext = context_course::instance($COURSE->id);
        
        //the order is: 
        //'My Dashboard'
        //'Trackers' 'Courses' 'Students' 'Team' 'Units' 'Reports' 'Assignments';
        //'Admin' 'Help' 'Feedback' 'Messages';
        $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB1, $tab, 'first');
        $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB2, $tab, 'middle');
//        if(($linkQualCourse = get_config('bcgt', 'linkqualcourse')) 
//                && has_capability('block/bcgt:viewcoursestab', $courseContext))
//        {
//            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB3, $tab, 'middle');
//        }
//        if(has_capability('block/bcgt:viewstudentstab', $courseContext))
//        {
//            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB4, $tab, 'middle');
//        }
//        if(has_capability('block/bcgt:viewteamtab', $courseContext))
//        {
//            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB5, $tab, 'middle');
//        }
//        if(has_capability('block/bcgt:viewunitstab', $courseContext))
//        {
//            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB6, $tab, 'middle');
//        }
//        if(has_capability('block/bcgt:viewreportsstab', $courseContext))
//        {
//            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB7, $tab, 'middle');
//        }
//        if(has_capability('block/bcgt:viewassignmentstab', $courseContext))
//        {
//            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB8, $tab, 'middle');
//        }
        if(has_capability('block/bcgt:viewadmintab', $courseContext))
        {
            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB9, $tab, 'middle');
        }
//        if(has_capability('block/bcgt:viewhelptab', $courseContext))
//        {
//            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB10, $tab, 'middle');
//        }
//        if(has_capability('block/bcgt:viewfeedbacktab', $courseContext))
//        {
//            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB11, $tab, 'middle');
//        }
//        if(has_capability('block/bcgt:viewmessagestab', $courseContext))
//        {
//            $retval .= DashTab::bcgt_core_get_core_dash_tab(DashTab::DASHTAB12, $tab, 'middle');
//        }
        return $retval;
    }
    
    public static function bcgt_core_get_core_dash_tab($tabName, $tabFocus, $class){
        global $CFG;
        $focus = ($tabFocus == $tabName ? true : false);
        $retval = '<li class="'.$class.'">'.
        '<a href="'.$CFG->wwwroot.'/blocks/bcgt/forms/my_dashboard.php?tab='.$tabName.'">'.
        '<span>'.get_string('dashtab'.$tabName, 'block_bcgt').'</span></a></li>';
        
        return $retval;
    }
    
    public static function bcgt_get_plugin_tabs($tabName)
    {
        //query the tabs table
        //get the records. For each get the file
        //get the class
        //call the bcgt_get_plugin_tabs method
        $classes = DashTab::get_plugin_classes();
        $retval = '';
        if($classes)
        {
            foreach($classes AS $class)
            {
                $retval .= $class::bcgt_get_plugin_tabs($tabName);
            }
        }
        return $retval;
    }
    
    public static function get_plugin_classes()
    {
        global $DB, $CFG;
        $classArray = array();
        $sql = "SELECT * FROM {block_bcgt_tabs} WHERE component != ?";
        $pluginTabs = $DB->get_records_sql($sql, array('core'));
        if($pluginTabs)
        {
            foreach($pluginTabs AS $tab)
            {
                $file = $CFG->dirroot.$tab->tabclassfile;
                $className = $tab->component;
                if (file_exists($file)) {
                    include_once($file);
                    $class = $className.'DashTab';
                    if(class_exists($class))
                    {
                        $classArray[] = $class;
                    }
                }
            }
        }
        return $classArray;
    }
    
    public static function bcgt_display_dashboard_tab_view($tabName, $courseID){
        switch($tabName)
        {
            case(DashTab::DASHTAB1):
                //then get the dashboard
                return DashTab::bcgt_tab_get_dashboard_tab();
                break;
            case(DashTab::DASHTAB2):
                //then get the trackers
                return DashTab::bcgt_tab_get_trackers_tab();
                break;
            case(DashTab::DASHTAB3):
                //then get the courses
                return DashTab::bcgt_tab_get_courses_tab();
                break;
            case(DashTab::DASHTAB4):
                //then get the students
                return DashTab::bcgt_tab_get_students_tab();
                break;
            case(DashTab::DASHTAB5):
                //then get the team
                return DashTab::bcgt_tab_get_team_tab();
                break;
            case(DashTab::DASHTAB6):
                //then get the units
                return DashTab::bcgt_tab_get_units_tab();
                break;
            case(DashTab::DASHTAB7):
                //then get the reports
                return DashTab::bcgt_tab_get_report_tab();
                break;
            case(DashTab::DASHTAB8):
                //then get the assignments
                return DashTab::bcgt_tab_get_assignments_tab();
                break;
            case(DashTab::DASHTAB9):
                //then get the admin
                require_once('AdminTab.class.php');
                $tab = new AdminTab();
                return $tab->get_tab_view($courseID);
                break;
            case(DashTab::DASHTAB10):
                //then get the help
                return DashTab::bcgt_tab_get_help_tab();
                break;
            case(DashTab::DASHTAB11):
                //then get the feedback
                return DashTab::bcgt_tab_get_feedback_tab();
                break;
            case(DashTab::DASHTAB12):
                //then get the messages
                return DashTab::bcgt_tab_get_messages_tab();
                break;
            default:
                return DashTab::bcgt_tab_get_plugin_tab($tabName);
                break;
        }
    }
    
    public static function bcgt_tab_get_plugin_tab($tabName)
    {
        $classes = DashTab::get_plugin_classes();
        $retval = '';
        if($classes)
        {
            foreach($classes AS $class)
            {
                //if this $tabName is not from this tab, then lets
                //move onto the next. If its not this will return false. 
                //so if it returns anything we have our tab.
                $tabFound = $class::bcgt_display_dashboard_tab_view($tabName);
                if($tabFound)
                {
                    $retval = $tabFound;
                    //then lets break out of the loop. 
                    break;
                }
            }
        }
        return $retval;
    }
    
    public static function get_plugin_title($tab)
    {
        $classes = DashTab::get_plugin_classes();
        $retval = '';
        if($classes)
        {
            foreach($classes AS $class)
            {
                $tabTitle = $class::bcgt_get_title($tab);
                if($tabTitle)
                {
                    $retval = $tabTitle;
                    break;
                }
            }
        }
        return $retval;
    }
    
    public static function bcgt_tab_get_dashboard_tab()
    {
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('bcgtmydashboard', 'block_bcgt').'</h2>';
        return $retval;
    }
    
    public static function bcgt_tab_get_courses_tab()
    {
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('mycourses', 'block_bcgt').'</h2>';
        return $retval;
    }
    
    public static function bcgt_tab_get_trackers_tab()
    {
        global $USER, $CFG, $PAGE, $DB;
        $jsModule = array(
            'name'     => 'block_bcgt',
            'fullpath' => '/blocks/bcgt/js/block_bcgt.js',
            'requires' => array('base', 'io', 'node', 'json', 'event')
        );
        $PAGE->requires->js_init_call('M.block_bcgt.inittrackerstab', null, true, $jsModule);
        $retval = '<div id="trackersDashContainer">';
        $retval .= '<h2 class="dashContentHeading">'.get_string('mytrackers', 'block_bcgt').'</h2>';
        $retval .= '<p>'.get_string('mytrackersdesc', 'block_bcgt').'</p>';
        //If they have the capibility to add themselves to trackers
        //then show that link
        if(has_capability('block/bcgt:viewallgrids', context_system::instance()))
        {
            $qualifications = search_qualification(-1, -1, -1, '', 
                -1, null, -1, null, true, null); 
        }
        else
        {
            $qualifications = get_role_quals($USER->id, array('teacher', 'editingteacher'));
        }
        if($qualifications)
        {
            foreach($qualifications AS $qual)
            {
                //is the qualification on a course?
                //does the qualification haave any students?
                $expand = true;
                $expandClass = '';
                $onCourse = $DB->get_records_sql('SELECT * FROM {block_bcgt_course_qual} WHERE bcgtqualificationid = ?', array($qual->id));
                if(!$onCourse)
                {
                   $expand = false;
                   $expandClass='no';
                }

                $class = '';
                $hasStudents = $DB->get_records_sql('SELECT userqual.id FROM {block_bcgt_user_qual} userqual 
                    JOIN {role} role ON role.id = userqual.roleid WHERE bcgtqualificationid = ? AND role.shortname = ?', 
                        array($qual->id, 'student'));
                if(!$hasStudents)
                {
                    $class = 'noStudents';
                }
                
                //if have the ability, then allow to add students to this qual. 
                $retval .= "<h3 class='simplequalreportheading$expandClass $class' id='sqrh_$qual->id'>";
                if($expand)
                {
                    $retval .= "<img src='$CFG->wwwroot/blocks/bcgt/pix/expandIcon.jpg'>".bcgt_get_qualification_display_name($qual, true, ' ')."";
                }
                $retval .= '</h3>';
                //ajax display call
                //expand
                //will get tabs and reporting. 
                $retval .= '<div class="simplequalreport" id="sqrc_'.$qual->id.'"></div>';

            }
        }
        $retval .= '</div>';
        return $retval;
    }
    
    public static function bcgt_tab_get_students_tab()
    {
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('mystudents', 'block_bcgt').'</h2>';
        return $retval;
    }
    
    public static function bcgt_tab_get_units_tab()
    {
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('myunits', 'block_bcgt').'</h2>';
        return $retval;
    }
    
    public static function bcgt_tab_get_team_tab()
    {
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('myteam', 'block_bcgt').'</h2>';
        return $retval;
    }
    
    public static function bcgt_tab_get_help_tab()
    {
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('help', 'block_bcgt').'</h2>';
        return $retval;
    }
    
    public static function bcgt_tab_get_report_tab()
    {
        global $DB, $USER;
        $courseID = optional_param('courseID', -1, PARAM_INT);
        $cID = optional_param('cID', -1, PARAM_INT);
        $studentID = optional_param('sID', -1, PARAM_INT);
        $search = optional_param('search', '', PARAM_TEXT);
        $qualID = optional_param('qID', -1, PARAM_INT);
        $stuSearch = optional_param('stusearch', '', PARAM_TEXT);
        $grade = optional_param('grade', '', PARAM_TEXT);
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('myreports', 'block_bcgt').'</h2>';
        
        //select a qual
        //get all quals or get 
        //select a course
        //search for a user and select them
        //list of reports
        //run. 
        if(has_capability('block/bcgt:viewallgrids', context_system::instance()))
        {
            $viewAll = true;
            $onCourse = null;
            if($courseID != -1)
            {
                $onCourse = true;
            }
            $quals = search_qualification(-1, -1, -1, '', 
                -1, null, -1, $onCourse, true); 
            $courses = bcgt_get_courses_with_quals(-1);
        }
        else
        {
            $teacher = $DB->get_record_select('role', 'shortname = ?', array('editingteacher'));
            $userQualRole = $DB->get_record_select('role', 'shortname = ?', array('teacher'));
            $quals = get_users_quals($USER->id, $userQualRole->id, '', -1, -1, null);
            if(!$quals)
            {
                $teacher = $DB->get_record_select('role', 'shortname = ?', array('editingteacher'));
                $quals = get_users_quals($USER->id, $teacher->id);
            }
            $courses = bcgt_get_users_courses($USER->id, $teacher->id, true, -1, null);
        }
        $retval .= '<form name="gridselect" action="" method="POST" id="gridselect">';
        $retval .= '<input type="hidden" id="cID" name="cID" value="'.$cID.'"/>';
        $retval .= '<div class="inputContainer"><div class="inputLeft">'.
                    '<label for="type">'.get_string('quals', 'block_bcgt').'</label></div>';
        $retval .= '<div class="inputRight"><select name="qID" id="qual">'.
                '<option value="-1">'.get_string('pleaseselect','block_bcgt').'</option>';
        if($quals)
        {    
            foreach($quals AS $qual)
            {
                if(count($quals) == 1)
                {
                    $qualID = $qual->id;
                }
                $selected = '';
                if(count($quals) == 1 || ($qualID != -1 && $qualID == $qual->id))
                {
                    $selected = 'selected';
                }
                $retval .= '<option '.$selected.' value="'.$qual->id.'">'.
                        bcgt_get_qualification_display_name($qual, true).'</option>';
            }
        }
        $retval .= '</select>';
        $retval .= '</div></div>';
        $retval .= '<div class="inputContainer"><div class="inputLeft">'.
                    '<label for="course">'.get_string('course').'</label></div>';
        $retval .= '<div class="inputRight"><select name="courseID" id="course">'.
                '<option value="-1">'.get_string('pleaseselect','block_bcgt').'</option>';
        if($courses)
        {    
            foreach($courses AS $course)
            {
                if(count($courses) == 1)
                {
                    $courseID = $course->id;
                }
                $selected = '';
                if(count($courses) == 1 || ($courseID != -1 && $courseID == $course->id))
                {
                    $selected = 'selected';
                }
                $retval .= '<option '.$selected.' value="'.$course->id.'">'.
                        $course->shortname.':'.$course->fullname.'</option>';
            }
        }
        $retval .= '</select>';
        $retval .= '</div></div>';
        //then have a student or qual searchable
        //drop down of all of their students
        if(!$viewAll)
        {
            $stuRole = $DB->get_record_select('role', 'shortname = ?', array('student'));
            $students = bcgt_get_users_users($USER->id, $userQualRole->id, $stuRole->id, $search);
            if($students)
            {
                $retval .= '<div class="inputContainer"><div class="inputLeft">'.
                '<label for="students">'.get_string('students', 'block_bcgt').'</label></div>';
                $retval .= '<div class="inputRight"><select name="sID" id="studentID">'.
                    '<option value="-1">'.get_string('pleaseselect','block_bcgt').'</option>'; 
                foreach($students AS $student)
                {
                    $selected = '';
                    if($student->id == $studentID)
                    {
                        $selected = 'selected';
                    }
                    $retval .= '<option '.$selected.' value="'.$student->id.'">'.
                            $student->username .' : '.$student->firstname.' '.$student->lastname.'</option>';
                }
                $retval .= '</select>';
                $retval .= '</div></div>';
            }
        }
        else {
            $retval .= '<div class="inputContainer"><div class="inputLeft">'.
                '<label for="stusearch">'.get_string('students', 'block_bcgt').'</label></div>';
                $retval .= '<div class="inputRight"><input type="text"'.
                        ' name="stusearch" id="stusearch" value="'.$stuSearch.'">';
                $retval .= '<input type="submit" name="search" value="'.get_string('search', 'block_bcgt').'"/>';
            $retval .= '</div></div>';
            if($stuSearch != '')
            {
                //then lets find the students
                $students = $DB->get_records_sql("SELECT * FROM {user} WHERE username ".
                        "LIKE ? OR firstname LIKE ? OR lastname LIKE ?", array('%'.$stuSearch.'%', '%'.$stuSearch.'%', '%'.$stuSearch.'%'));
                if($students)
                {
                    $retval .= '<div class="inputContainer"><div class="inputLeft">'.
                    '<label for="students">'.get_string('students', 'block_bcgt').'</label></div>';
                    $retval .= '<div class="inputRight"><select name="sID" id="studentID">'.
                        '<option value="-1">'.get_string('pleaseselect','block_bcgt').'</option>'; 
                    foreach($students AS $student)
                    {
                        $selected = '';
                        if($student->id == $studentID)
                        {
                            $selected = 'selected';
                        }
                        $retval .= '<option '.$selected.' value="'.$student->id.'">'.
                                $student->username .' : '.$student->firstname.' '.$student->lastname.'</option>';
                    }
                    $retval .= '</select>';
                    $retval .= '</div></div>';
                }
                
            }
        }
        
        
        //get a list of the reports. 
        $reporting = new Reporting();
        $retval .= '<div class="inputContainer"><div class="inputLeft">'.
                    '<label for="report">'.get_string('report', 'block_bcgt').'</label></div>';
        $retval .= '<div class="inputRight">';
        $retval .= $reporting->get_reports_drop_down();
        $retval .= '</div></div>';
        
        $retval .= '<div class="inputContainer"><div class="inputLeft">'.
                    '<label for="grade">'.get_string('gradetype', 'block_bcgt').'</label></div>';
        $retval .= '<div class="inputRight">';
        $retval .= '<select name="grade">';
        $selected = '';
        if($grade == 'full')
        {
            $selected = 'selected';
        }
        $retval .= '<option '.$selected.' value="full">'.get_string('gradetypealps','block_bcgt').'</option>';
        $selected = '';
        if($grade == 'weight')
        {
            $selected = 'selected';
        }
        $retval .= '<option '.$selected.' value="weight">'.get_string('gradetypeweighted','block_bcgt').
                '</option>';
        $selected = '';
        if($grade == 'teach')
        {
            $selected = 'selected';
        }
        $retval .= '<option '.$selected.' value="teach">'.get_string('gradetypealps','block_bcgt').'</option></select>';
        $retval .= '</div></div>';
        $retval .= '<input type="submit" name="run" value="Fetch Report"/>';
        $retval .= '</form>';
        
        if(isset($_POST['run']))
        {
            $retval .= $reporting->get_report();
        }
        return $retval;
    }
    
    public static function bcgt_tab_get_feedback_tab()
    {
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('feedback', 'block_bcgt').'</h2>';
        return $retval;
    }
    
    public static function bcgt_tab_get_messages_tab()
    {
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('messages', 'block_bcgt').'</h2>';
        return $retval;
    }
    
    public static function bcgt_tab_get_assignments_tab()
    {
        $retval = '';
        $retval .= '<h2 class="dashContentHeading">'.get_string('myassignments', 'block_bcgt').'</h2>';
        return $retval;
    }
    
    
    
}
?>
