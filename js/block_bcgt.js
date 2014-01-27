/*
 * Moodle Gradetracker V1.0 – This code is copyright of Bedford College and is 
 * supplied for evaluation purposes only. The code may not be used for any 
 * purpose without permission from The Learning Technologies Team, 
 * Bedford College:  moodlegrades@bedford.ac.uk
 * 
 * Author mchaney@bedford.ac.uk
 */

M.block_bcgt = {};
 
M.block_bcgt.init = function(Y) {
    
    //all of the initialised functions required. 
//    var searchButton = Y.one('#search');
//    searchButton.on('click', helloWorld);
//    
//    var searchText = Y.one('#searchStudent');
//    searchText.on('change', helloWorld);

    //go to course:
//    
//    var goToCourse = $('#gotocourse');
//    if(goToCourse)
//    {
//        $('#gotocourse').on('change',function(){
//           //then we want to go to that course (unless its -1)
//            var courseID = $(this).val();
//            if(courseID != -1)
//            {
//                location = '../../../course/view.php?id='+courseID;
//            }
//        });
//    }

};

M.block_bcgt.inittrackerstab = function(Y) {
    
    var buttons = Y.all('.simplequalreportheading');
    if(buttons)
    {
        buttons.each( function(button){
            button.on('click', function(event){
                var qual = button.getAttribute('id');
                //sqrh_$qual->id
                var quals = qual.split("_");
                var qualID = quals[1];
                
                var data = {
                    method: 'POST',
                    data: {
                        'qID' : qualID 
                    },
                    dataType: 'json',
                    on: {
                        success: display_simple_qual_report
                    }
                }
                var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/get_simple_qual_report.php";
                var request = Y.io(url, data);
                
            });
        });
    }
    applyReportingTT();
    //all of the initialised functions required. 
//    var searchButton = Y.one('#search');
//    searchButton.on('click', helloWorld);
//    
//    var searchText = Y.one('#searchStudent');
//    searchText.on('change', helloWorld);
};

function display_simple_qual_report(id, o)
{
    var data = o.responseText; // Response data.
    var json = Y.JSON.parse(o.responseText);
    if(json.retval != null)
    {
        var qualID = json.qualid;
        var display = json.retval;
        var div = Y.one('#sqrc_'+qualID);
        if(div)
        {
            div.set('innerHTML', display);
        }
    }   
    applyReportingTT();
}

function applyReportingTT()
{
    var tabs = Y.all('.tab');
    if(tabs)
    {
        tabs.each( function(tab){
            tab.on('click', function(event){
                var qualID = tab.getAttribute('qual');
                var tabType = tab.getAttribute('tab');
                var data = {
                    method: 'POST',
                    data: {
                        'qID' : qualID,
                        'tab' : tabType
                    },
                    dataType: 'json',
                    on: {
                        success: display_simple_qual_report
                    }
                }
                var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/get_simple_qual_report.php";
                var request = Y.io(url, data);
                
            });
        });
    }
    
    var edit = Y.one('#edit');
    if(edit)
    {
        edit.on('click', function(event){
            event.preventDefault();
            var qualID = edit.getAttribute('qual');
            var tabType = edit.getAttribute('tab');
            var data = {
                method: 'POST',
                data: {
                    'qID' : qualID,
                    'tab' : tabType,
                    'edit' : true
                },
                dataType: 'json',
                on: {
                    success: display_simple_qual_report
                }
            }
            var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/get_simple_qual_report.php";
            var request = Y.io(url, data);

        });
    }
    
    var view = Y.one('#view');
    if(view)
    {
        view.on('click', function(event){
            event.preventDefault();
            var qualID = view.getAttribute('qual');
            var tabType = view.getAttribute('tab');
            var data = {
                method: 'POST',
                data: {
                    'qID' : qualID,
                    'tab' : tabType,
                    'edit' : false
                },
                dataType: 'json',
                on: {
                    success: display_simple_qual_report
                }
            }
            var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/get_simple_qual_report.php";
            var request = Y.io(url, data);

        });
    }
    
    var edittargets = Y.all('.edittarget');
    if(edittargets)
    {
        edittargets.each( function(edittarget){
            edittarget.on('change', function(event){
                var qualID = edittarget.getAttribute('qual');
                var sID = edittarget.getAttribute('sid');
                var index = Y.one("#t_"+qualID+"_s_"+sID).get('selectedIndex');
                var value = Y.one("#t_"+qualID+"_s_"+sID).get("options").item(index).getAttribute('value');
                var cID = edittarget.getAttribute('cid');
                var type = edittarget.getAttribute('type');
                var index = Y.one("#uf_"+qualID).get('selectedIndex');
                var ufilter = Y.one("#uf_"+qualID).get("options").item(index).getAttribute('value');
                var index = Y.one("#tf_"+qualID).get('selectedIndex');
                var tfilter = Y.one("#tf_"+qualID).get("options").item(index).getAttribute('value');
                var data = {
                    method: 'POST',
                    data: {
                        'qID' : qualID,
                        'ufilter' : ufilter,
                        'tfilter' : tfilter,
                        'value' : value,
                        'type' : type,
                        'sID' : sID,
                        'cID' : cID
                    },
                    dataType: 'json',
                    on: {
//                        success: display_simple_qual_report
                    }
                }
                var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/update_user_target.php";
                var request = Y.io(url, data);
                
            });
        });
    }
    
    var editasps = Y.all('.editasp');
    if(editasps)
    {
        editasps.each( function(editasp){
            editasp.on('change', function(event){
                var qualID = editasp.getAttribute('qual');
                var sID = editasp.getAttribute('sid');
                var index = Y.one("#a_"+qualID+"_s_"+sID).get('selectedIndex');
                var value = Y.one("#a_"+qualID+"_s_"+sID).get("options").item(index).getAttribute('value');
                var cID = editasp.getAttribute('cid');
                var type = editasp.getAttribute('type');
                var index = Y.one("#uf_"+qualID).get('selectedIndex');
                var ufilter = Y.one("#uf_"+qualID).get("options").item(index).getAttribute('value');
                var index = Y.one("#tf_"+qualID).get('selectedIndex');
                var tfilter = Y.one("#tf_"+qualID).get("options").item(index).getAttribute('value');
                var data = {
                    method: 'POST',
                    data: {
                        'qID' : qualID,
                        'ufilter' : ufilter,
                        'tfilter' : tfilter,
                        'value' : value,
                        'type' : type,
                        'sID' : sID,
                        'cID' : cID
                    },
                    dataType: 'json',
                    on: {
//                        success: display_simple_qual_report
                    }
                }
//                alert(JSON.stringfy(data));
                var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/update_user_target.php";
                var request = Y.io(url, data);
                
            });
        });
    }
    
    var unitFilters = Y.all('.unitFilter');
    if(unitFilters)
    {
        unitFilters.each( function(unitFilter){
            unitFilter.on('change', function(event){
                var qualID = unitFilter.getAttribute('qual');
                var editing = Y.one("#editing").get("value");
                var index = Y.one("#uf_"+qualID).get('selectedIndex');
                var ufilter = Y.one("#uf_"+qualID).get("options").item(index).getAttribute('value');
                var index = Y.one("#tf_"+qualID).get('selectedIndex');
                var tfilter = Y.one("#tf_"+qualID).get("options").item(index).getAttribute('value');
                var tabType = unitFilter.getAttribute('tab');
                var data = {
                    method: 'POST',
                    data: {
                        'qID' : qualID,
                        'ufilter' : ufilter,
                        'tfilter' : tfilter,
                        'tab' : tabType,
                        'edit' : editing
                    },
                    dataType: 'json',
                    on: {
                        success: display_simple_qual_report
                    }
                }
                var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/get_simple_qual_report.php";
                var request = Y.io(url, data);
                
            });
        });
    }
    
    var targetFilters = Y.all('.targetFilter');
    if(targetFilters)
    {
        targetFilters.each( function(targetFilter){
            targetFilter.on('change', function(event){
                var qualID = targetFilter.getAttribute('qual');
                var editing = Y.one("#editing").get("value");
                var index = Y.one("#uf_"+qualID).get('selectedIndex');
                var ufilter = Y.one("#uf_"+qualID).get("options").item(index).getAttribute('value');
                var index = Y.one("#tf_"+qualID).get('selectedIndex');
                var tfilter = Y.one("#tf_"+qualID).get("options").item(index).getAttribute('value');
                var tabType = targetFilter.getAttribute('tab');
                var data = {
                    method: 'POST',
                    data: {
                        'qID' : qualID,
                        'ufilter' : ufilter,
                        'tfilter' : tfilter,
                        'tab' : tabType,
                        'edit' : editing
                    },
                    dataType: 'json',
                    on: {
                        success: display_simple_qual_report
                    }
                }
                var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/get_simple_qual_report.php";
                var request = Y.io(url, data);
                
            });
        });
    }
    
    var sortheads = Y.all('.sorthead');
    if(sortheads)
    {
        sortheads.each( function(sorthead){
            sorthead.on('click', function(event){
                event.preventDefault();
                var qualID = sorthead.getAttribute('qual');
                var editing = Y.one("#editing").get("value");
                var index = Y.one("#uf_"+qualID).get('selectedIndex');
                var ufilter = Y.one("#uf_"+qualID).get("options").item(index).getAttribute('value');
                var index = Y.one("#tf_"+qualID).get('selectedIndex');
                var tfilter = Y.one("#tf_"+qualID).get("options").item(index).getAttribute('value');
                var tabType = sorthead.getAttribute('tab');
                var currentSort = Y.one("#sorting").get("value");
                var thisSort = sorthead.getAttribute('sortname');
                var currentSort = currentSort + ',' + thisSort;
                var data = {
                    method: 'POST',
                    data: {
                        'qID' : qualID,
                        'ufilter' : ufilter,
                        'tfilter' : tfilter,
                        'tab' : tabType,
                        'edit' : editing,
                        'sort' : currentSort
                    },
                    dataType: 'json',
                    on: {
                        success: display_simple_qual_report
                    }
                }
                var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/get_simple_qual_report.php";
                var request = Y.io(url, data);
                
            });
        });
    }
    
    var usortheads = Y.all('.usorthead');
    if(usortheads)
    {
        usortheads.each( function(sorthead){
            sorthead.on('click', function(event){
                event.preventDefault();
                var qualID = sorthead.getAttribute('qual');
                var tabType = sorthead.getAttribute('tab');
                var currentSort = Y.one("#usorting").get("value");
                var thisSort = sorthead.getAttribute('sortname');
                var currentSort = currentSort + ',' + thisSort;
                var data = {
                    method: 'POST',
                    data: {
                        'qID' : qualID,
                        'tab' : tabType,
                        'sort' : currentSort
                    },
                    dataType: 'json',
                    on: {
                        success: display_simple_qual_report
                    }
                }
                var url = M.cfg.wwwroot+"/blocks/bcgt/ajax/get_simple_qual_report.php";
                var request = Y.io(url, data);
                
            });
        });
    }
}

M.block_bcgt.initeditqual = function(Y) {
    Y.one('#save').set('disabled', 'disabled');
    var qualFamily = Y.one('#qualFamilySelect');
    qualFamily.on('change', reloadEditQualForm);    
    
    var qualPath = Y.one('#qualPathway');
    if (qualPath != null){
        qualPath.on('change', reloadEditQualForm);
    }
    
    
    var qualPathType = Y.one('#qualPathwayType');
    if (qualPathType != null){
        qualPathType.on('change', reloadEditQualForm);
    }
    
    var q = Y.one('#qualPathwaySubType');
    if (q != null){
        q.on('change', reloadEditQualForm);
    }
    
    var name = Y.one('#qualName');
    $('#qualName').unbind('keypress');
    name.on('keypress', function(e){
        check_edit_qual_valid();
    })
    check_edit_qual_valid();
};

function check_edit_qual_valid()
{
    //get the level and subtype and name
    var typeIndex = Y.one('#qualFamilySelect').get('selectedIndex');
    var type = Y.one("#qualFamilySelect").get("options").item(typeIndex).getAttribute('value');
    var name = Y.one('#qualName').get('value');
    if(name != '' && name != ' ' && type != -1)
    {
        Y.one('#save').set('disabled', '');
    }
}

M.block_bcgt.initselqual = function(Y) {
    var qualFamily = Y.one('#family');
    qualFamily.on('change', function(e) {
        Y.one('#bcgtQualSelect').submit();
    });
    
    var level = Y.one('#level');
    level.on('change', function(e) {
        Y.one('#bcgtQualSelect').submit();
    });
    
    var subtype = Y.one('#subtype');
    subtype.on('change', function(e) {
        Y.one('#bcgtQualSelect').submit();
    });
}

M.block_bcgt.initselunit = function(Y) {
    
}

M.block_bcgt.initeditunit = function(Y) {
    Y.one('#save').set('disabled', 'disabled');
    var qualFamily = Y.one('#unitTypeFamily');
    qualFamily.on('change', function(e) {
        Y.one('#editUnitForm').submit();
    });
    var unique = Y.one('#unique');
    $('#unique').unbind('keypress');
    if(unique)
    {
        unique.on('keypress', function(e){
                check_edit_unit_valid();
        })
    }
    
    var name = Y.one('#name');
    $('#name').unbind('keypress');
    name.on('keypress', function(e){
        check_edit_unit_valid();
    })
    check_edit_unit_valid();
}

function check_edit_unit_valid()
{
    //get the level and subtype and name
    var typeIndex = Y.one('#unitTypeFamily').get('selectedIndex');
    var type = Y.one("#unitTypeFamily").get("options").item(typeIndex).getAttribute('value');
    var uniqueInput = Y.one('#unique');
    if(uniqueInput)
    {
        var unique = Y.one('#unique').get('value');
    }
    var name = Y.one('#name').get('value');
    if(name != '' && ((uniqueInput && unique != '') || (!uniqueInput)) && type != -1)
    {
        Y.one('#save').set('disabled', '');
    }
}

M.block_bcgt.initqualunits = function(Y) {
    var addSelect = Y.one('#addselect');
    addSelect.on('click', function(e) {
        Y.one('#addUnit').set('disabled', false);
        Y.one('#removeUnit').set('disabled', true);
        Y.one('#removeselect').get('options').set('selected',false);
        var multipleSelected = checkMultipleSelects(Y.one('#addselect'));
        if(multipleSelected == 1)
        {
            //if there is only one selected then enable the edit button
            Y.one('#editUnit').set('disabled', false);
        }
        else
        {
            //else disable the edit button
            Y.one('#editUnit').set('disabled', true);
        }
    }); 
    
    var removeSelect = Y.one('#removeselect');
    removeSelect.on('click', function(e) {
        Y.one('#addUnit').set('disabled', true);
        Y.one('#removeUnit').set('disabled', false);
        Y.one('#addselect').get('options').set('selected',false);
        var multipleSelected = checkMultipleSelects(Y.one('#removeselect'));
        if(multipleSelected == 1)
        {
            //if there is only one selected then enable the edit button
            Y.one('#editUnit').set('disabled', false);
        }
        else
        {
            //else disable the edit button
            Y.one('#editUnit').set('disabled', true);
        }
    }); 
}

M.block_bcgt.initprojectquals = function(Y) {
    var addSelect = Y.one('#addselect');
    addSelect.on('click', function(e) {
        Y.one('#addQual').set('disabled', false);
        Y.one('#removeQual').set('disabled', true);
        Y.one('#removeselect').get('options').set('selected',false);
    }); 
    
    var removeSelect = Y.one('#removeselect');
    removeSelect.on('click', function(e) {
        Y.one('#addQual').set('disabled', true);
        Y.one('#removeQual').set('disabled', false);
        Y.one('#addselect').get('options').set('selected',false);
    }); 
    
    // Destroy datepickers and recreate them
    $('.bcgt_datepicker').datepicker( {dateFormat: 'dd-mm-yy', changeMonth: true, changeYear: true} );
    
//    var addQual = Y.one('#addQual');
//    addQual.on('click', function(e) {
//        e.preventDefault();
//        //we need to get the options and append these to the
//        //list on the left
//        
//        
//    //then add each value to an array
//    });
//    
//    var removeQual = Y.one('#removeQual');
//    removeQual.on('click', function(e) {
//        e.preventDefault();
//    });
    
}

M.block_bcgt.initqualteachers = function(Y) {
    var addSelect = Y.one('#addselect');
    addSelect.on('click', function(e) {
        Y.one('#addTeacher').set('disabled', false);
        Y.one('#removeTeacher').set('disabled', true);
        Y.one('#removeselect').get('options').set('selected',false);
    }); 
    
    var removeSelect = Y.one('#removeselect');
    removeSelect.on('click', function(e) {
        Y.one('#addTeacher').set('disabled', true);
        Y.one('#removeTeacher').set('disabled', false);
        Y.one('#addselect').get('options').set('selected',false);
    }); 
    
    var qualSelect = Y.one('#qualSelect');
    qualSelect.on('click', function(e) {
        Y.one('#editQualTeacher').submit();
    });
}

M.block_bcgt.initteacherquals = function(Y) {
    var addSelect = Y.one('#addselect');
    addSelect.on('click', function(e) {
        Y.one('#addQual').set('disabled', false);
        Y.one('#removeQual').set('disabled', true);
        Y.one('#removeselect').get('options').set('selected',false);
    }); 
    
    var removeSelect = Y.one('#removeselect');
    removeSelect.on('click', function(e) {
        Y.one('#addQual').set('disabled', true);
        Y.one('#removeQual').set('disabled', false);
        Y.one('#addselect').get('options').set('selected',false);
    }); 
    
    var qualSelect = Y.one('#userID');
    qualSelect.on('click', function(e) {
        Y.one('#editUserQual').submit();
    });
}

M.block_bcgt.initqualstud = function(Y) {
    var addSelect = Y.one('#addselect');
    addSelect.on('click', function(e) {
        Y.one('#addStu').set('disabled', false);
        Y.one('#removeStu').set('disabled', true);
        Y.one('#removeselect').get('options').set('selected',false);
    }); 
    
    var removeSelect = Y.one('#removeselect');
    removeSelect.on('click', function(e) {
        Y.one('#addStu').set('disabled', true);
        Y.one('#removeStu').set('disabled', false);
        Y.one('#addselect').get('options').set('selected',false);
    }); 
}

M.block_bcgt.initunitquals = function(Y) {
    var addSelect = Y.one('#addselect');
    addSelect.on('click', function(e) {
        Y.one('#addQual').set('disabled', false);
        Y.one('#removeQual').set('disabled', true);
        Y.one('#removeselect').get('options').set('selected',false);
    }); 
    
    var removeSelect = Y.one('#removeselect');
    removeSelect.on('click', function(e) {
        Y.one('#addQual').set('disabled', true);
        Y.one('#removeQual').set('disabled', false);
        Y.one('#addselect').get('options').set('selected',false);
    }); 
    
    var unitSelect = Y.one('#unitID');
    unitSelect.on('click', function(e) {
        Y.one('#editUnitQualForm').submit();
    });
}

M.block_bcgt.initusersusers = function(Y) {
    var addSelect = Y.one('#addselect');
    addSelect.on('click', function(e) {
        Y.one('#addUser').set('disabled', false);
        Y.one('#removeUser').set('disabled', true);
        Y.one('#removeselect').get('options').set('selected',false);
    }); 
    
    var removeSelect = Y.one('#removeselect');
    removeSelect.on('click', function(e) {
        Y.one('#addUser').set('disabled', true);
        Y.one('#removeUser').set('disabled', false);
        Y.one('#addselect').get('options').set('selected',false);
    }); 
    
    var unitSelect = Y.one('#userID');
    unitSelect.on('click', function(e) {
        Y.one('#editUserUsers').submit();
    });
}


M.block_bcgt.initselcourse = function(Y) {
    
}

M.block_bcgt.initcoursequals = function(Y) {
    var addSelect = Y.one('#addselect');
    addSelect.on('click', function(e) {
        Y.one('#addQual').set('disabled', false);
        Y.one('#removeQual').set('disabled', true);
        Y.one('#removeselect').get('options').set('selected',false);
    }); 
    
    var removeSelect = Y.one('#removeselect');
    removeSelect.on('click', function(e) {
        Y.one('#addQual').set('disabled', true);
        Y.one('#removeQual').set('disabled', false);
        Y.one('#addselect').get('options').set('selected',false);
    }); 
    
}

M.block_bcgt.initcoursequalsusers = function(Y) {
    $(document).ready( function () {
        var tables = $('.courseQualUserTable');
        var count = tables.length;
        var tablesArray = [];
        for(var i=1;i<=count;i++)
        {
            tablesArray[i] = $('#courseQualUserTable'+i).dataTable( {
                "sScrollX": "100%",
                "sScrollY": "800px",
                "bScrollCollapse": true,
                "bPaginate": false,
                "bSort": false,
                "bInfo":false
            });
                
            new FixedColumns( tablesArray[i], {
                "iLeftColumns": 4,
                "iLeftWidth": 260 
           }); 
        }
        var staffTable = $('#courseQualUserTableStaff');
        if(staffTable.length)
        {
            var staffDataTable = $('#courseQualUserTableStaff').dataTable( {
                "sScrollX": "100%",
                "sScrollY": "800px",
                "bScrollCollapse": true,
                "bPaginate": false,
                "bSort": false,
                "bInfo":false
            });
            
            if(staffDataTable)
            {
                new FixedColumns( staffDataTable, {
                    "iLeftColumns": 4,
                    "iLeftWidth": 260 
               });
            }
            
        }
         
        
    });
    
    
    //When the all button is clicked for the staff
    var qualStaffAll = Y.all('.qualColumnStaffAll');
    qualStaffAll.each(function(qual){
        qual.on('click', function(e){
            e.preventDefault();
            //id is in the form of qID
            var id = qual.get('id');
            var toggle = true;
            var className = qual.getAttribute('class');
            var toggleOn = className.indexOf('tOn');
            var toggleOff = className.indexOf('tOff');
            if(toggleOn == -1 && toggleOff == -1)
            {
                className = className + 'tOn'; 
            }
            else
            {
                //knock off the tOn or tOff and put the other back
                //then swicth what we are doing checking or unchecking
                if(toggleOn != -1)
                {
                    className = className.substring(0,className.indexOf('tOn'));
                    toggle = false;
                    className = className + 'tOff';
                }
                else
                {
                    className = className.substring(0,className.indexOf('tOff'));
                    className = className + 'tOn';
                }
            }
            var checkboxes = Y.all('.ch'+id);
            checkboxes.each(function(input){
                if(!toggle)
                {
                    input.set('checked', '');
                }
                else
                {
                    input.set('checked', 'checked'); 
                } 
            });
            qual.setAttribute('class', className);
            //get the id of it so we can get the unitid
            //then find all of the checkboxes that have a class that contains
            //uUnitID and set them to checked. 
        });
    });
    
    //when the all buton is clicked for the students
    var qualAll = Y.all('.qualColumnAll');
    qualAll.each(function(qual){
        qual.on('click', function(e){
            e.preventDefault();
            //id is in the form of qID
            var id = qual.get('id');
            var toggle = true;
            var className = qual.getAttribute('class');
            var toggleOn = className.indexOf('tOn');
            var toggleOff = className.indexOf('tOff');
            if(toggleOn == -1 && toggleOff == -1)
            {
                className = className + 'tOn'; 
            }
            else
            {
                //knock off the tOn or tOff and put the other back
                //then swicth what we are doing checking or unchecking
                if(toggleOn != -1)
                {
                    className = className.substring(0,className.indexOf('tOn'));
                    toggle = false;
                    className = className + 'tOff';
                }
                else
                {
                    className = className.substring(0,className.indexOf('tOff'));
                    className = className + 'tOn';
                }
            }
            var checkboxes = Y.all('.ch'+id);
            checkboxes.each(function(input){
                if(!toggle)
                {
                    input.set('checked', '');
                }
                else
                {
                    input.set('checked', 'checked'); 
                } 
            });
            qual.setAttribute('class', className);
            //get the id of it so we can get the unitid
            //then find all of the checkboxes that have a class that contains
            //uUnitID and set them to checked. 
        });
    });
    
    //when the summaryAll is clicked for the unlinked students
    var unlinkedQual = Y.all('.qualUnlinkedColumnCourse');
    unlinkedQual.each(function(qual){
        qual.on('click', function(e){
            e.preventDefault();
            //id is in the form of qID
            var id = qual.get('id');
            var toggle = true;
            var className = qual.getAttribute('class');
            var toggleOn = className.indexOf('tOn');
            var toggleOff = className.indexOf('tOff');
            if(toggleOn == -1 && toggleOff == -1)
            {
                className = className + 'tOn'; 
            }
            else
            {
                //knock off the tOn or tOff and put the other back
                //then swicth what we are doing checking or unchecking
                if(toggleOn != -1)
                {
                    className = className.substring(0,className.indexOf('tOn'));
                    toggle = false;
                    className = className + 'tOff';
                }
                else
                {
                    className = className.substring(0,className.indexOf('tOff'));
                    className = className + 'tOn';
                }
            }
            var checkboxes = Y.all('.ch'+id);
            checkboxes.each(function(input){
                if(!toggle)
                {
                    input.set('checked', '');
                }
                else
                {
                    input.set('checked', 'checked'); 
                } 
            });
            qual.setAttribute('class', className);
            //get the id of it so we can get the unitid
            //then find all of the checkboxes that have a class that contains
            //uUnitID and set them to checked. 
        });
    });
    
    //when the summary button is clicked for staf (e.g by specific course)
    var staffQual = Y.all('.qualColumnCourseStaff');
    staffQual.each(function(qual){
        qual.on('click', function(e){
            e.preventDefault();
            //id is in the form of qID
            var id = qual.get('id');
            var toggle = true;
            var className = qual.getAttribute('class');
            var toggleOn = className.indexOf('tOn');
            var toggleOff = className.indexOf('tOff');
            if(toggleOn == -1 && toggleOff == -1)
            {
                className = className + 'tOn'; 
            }
            else
            {
                //knock off the tOn or tOff and put the other back
                //then swicth what we are doing checking or unchecking
                if(toggleOn != -1)
                {
                    className = className.substring(0,className.indexOf('tOn'));
                    toggle = false;
                    className = className + 'tOff';
                }
                else
                {
                    className = className.substring(0,className.indexOf('tOff'));
                    className = className + 'tOn';
                }
            }
            var checkboxes = Y.all('.ch'+id);
            checkboxes.each(function(input){
                if(!toggle)
                {
                    input.set('checked', '');
                }
                else
                {
                    input.set('checked', 'checked'); 
                } 
            });
            qual.setAttribute('class', className);
            //get the id of it so we can get the unitid
            //then find all of the checkboxes that have a class that contains
            //uUnitID and set them to checked. 
        });
    });
    
    var qualCourse = Y.all('.qualColumnCourse');
    qualCourse.each(function(qual){
        qual.on('click', function(e){
            e.preventDefault();
            //id is in the form of qID
            var id = qual.get('id');
            var toggle = true;
            var className = qual.getAttribute('class');
            var toggleOn = className.indexOf('tOn');
            var toggleOff = className.indexOf('tOff');
            if(toggleOn == -1 && toggleOff == -1)
            {
                className = className + 'tOn'; 
            }
            else
            {
                //knock off the tOn or tOff and put the other back
                //then swicth what we are doing checking or unchecking
                if(toggleOn != -1)
                {
                    className = className.substring(0,className.indexOf('tOn'));
                    toggle = false;
                    className = className + 'tOff';
                }
                else
                {
                    className = className.substring(0,className.indexOf('tOff'));
                    className = className + 'tOn';
                }
            }
            var checkboxes = Y.all('.ch'+id);
            checkboxes.each(function(input){
                if(!toggle)
                {
                    input.set('checked', '');
                }
                else
                {
                    input.set('checked', 'checked'); 
                } 
            });
            qual.setAttribute('class', className);
            //get the id of it so we can get the unitid
            //then find all of the checkboxes that have a class that contains
            //uUnitID and set them to checked. 
        });
    });
    
    var studentRow = Y.all('.studentRow');
    studentRow.each(function(student){
        student.on('click', function(e){
            e.preventDefault();
            //id is in the form of qID
            var id = student.get('id');
            var toggle = true;
            var className = student.getAttribute('class');
            var toggleOn = className.indexOf('tOn');
            var toggleOff = className.indexOf('tOff');
            if(toggleOn == -1 && toggleOff == -1)
            {
                className = className + 'tOn'; 
            }
            else
            {
                //knock off the tOn or tOff and put the other back
                //then swicth what we are doing checking or unchecking
                if(toggleOn != -1)
                {
                    className = className.substring(0,className.indexOf('tOn'));
                    toggle = false;
                    className = className + 'tOff';
                }
                else
                {
                    className = className.substring(0,className.indexOf('tOff'));
                    className = className + 'tOn';
                }
            }
            var checkboxes = Y.all('.ch'+id);
            checkboxes.each(function(input){
                if(!toggle)
                {
                    input.set('checked', '');
                }
                else
                {
                    input.set('checked', 'checked'); 
                } 
            });
            student.setAttribute('class', className);
            //get the id of it so we can get the unitid
            //then find all of the checkboxes that have a class that contains
            //uUnitID and set them to checked. 
        });
    });
}

M.block_bcgt.initgridselect = function(Y) {
    var qual = Y.one('#qual');
    qual.on('change', function(e) {
        var index = Y.one("#qual").get('selectedIndex');
        var qualID = Y.one("#qual").get("options").item(index).getAttribute('value');
        var grid = Y.one('#grid').get('value');
        if(qualID != -1 && (grid == 'c' || grid == 'a'))
        {
            e.preventDefault();
            var cID = Y.one("#cID").get('value');
            
            var index2 = Y.one("#course").get('selectedIndex');
            var courseID = Y.one("#course").get("options").item(index2).getAttribute('value');
            //then location will be the subject grid with the qualid passed in
            if(grid == 'c')
            {
                 location = '../grids/class_grid.php?qID='+qualID+'&cID='+cID+'&g=c';   
            }
            else
            {
                location = '../grids/ass_grid_class.php?qID='+qualID+'&cID='+cID+'&g=a';
            }
        }
        else
        {
            Y.one('#gridselect').submit();
        }
    });
    
    var course = Y.one('#course');
    course.on('change', function(e) {
        Y.one('#gridselect').submit();
    });
    
    var acourse = Y.one('#acourse');
    if(acourse)
    {
        acourse.on('change', function(e) {
        Y.one('#gridselect').submit();
        });
    }
    
    var aqual = Y.one('#aqual');
    if(aqual)
    {
        aqual.on('change', function(e) {
                var index = Y.one("#aqual").get('selectedIndex');
                var qualID = Y.one("#aqual").get("options").item(index).getAttribute('value');
                var grid = Y.one('#grid').get('value');
                if(qualID != -1 && (grid == 'c' || grid == 'a'))
                {
                    e.preventDefault();
                    var cID = Y.one("#cID").get('value');
                    //then location will be the subject grid with the qualid passed in
                    if(grid == 'c')
                    {
                         location = '../grids/class_grid.php?qID='+qualID+'&cID='+cID+'&g=c';   
                    }
                    else
                    {
                        location = '../grids/ass_grid_class.php?qID='+qualID+'&cID='+cID+'&g=a';
                    }
                }
                else
                {
                    Y.one('#gridselect').submit();
                }
            });
    }
    
    
    var student = Y.one('#studentID');
    if(student)
    {
        student.on('change', function(e) {
        Y.one('#gridselect').submit();
        });
    }
    
    var unit = Y.one('#unitID');
    if(unit)
    {
        unit.on('change', function(e) {
        Y.one('#gridselect').submit();
        });
    }
    
    var assessment = Y.one('#assID');
    if(assessment)
    {
        assessment.on('change', function(e) {
        Y.one('#gridselect').submit();
        });
    }
    
    var projectQuals = Y.all('.projectQualSelect');
    if(projectQuals)
    {
        var cID = Y.one("#cID").get('value');
        projectQuals.each(function(project){
            project.on('change', function (e){
                //need to get the projectID
                //and the qualid
                //the qual id is the value selected
                //the projectID is the id of the select
                var index = project.get('selectedIndex');
                var qualID = project.get("options").item(index).getAttribute('value');
                
                var projectID = project.get("id");
                location = '../grids/ass.php?qID='+qualID+'&cID='+cID+'&g=a&pID='+projectID;
                
            });
        });
    }
}

M.block_bcgt.initgridstu = function(Y) {
    var student = Y.one('#studentChange');
    if(student)
    {
        student.on('change', function(e) {
            Y.one('#studentGridForm').submit();
        });
    }

    var qual = Y.one('#qualChange');
    if(qual)
    {
        qual.on('change', function(e) {
            var index = Y.one("#qualChange").get('selectedIndex');
            var qualID = Y.one("#qualChange").get("options").item(index).getAttribute('value');
            location = '../forms/grid_select.php?qID='+qualID;
        });
    }
    
}

M.block_bcgt.initimport = function(Y) {
    var qual = Y.one('#famquals');
    if(qual)
    {
        qual.on('change', function(e) {
        Y.one('#importform').submit();
        });
    }
}


M.block_bcgt.initgridunit = function(Y) {
    var student = Y.one('#unitChange');
    if (student != null){
        student.on('change', function(e) {
            Y.one('#unitGridForm').submit();
        });
    }
    
    var qual = Y.one('#qualChange');
    if (qual != null){
        qual.on('change', function(e) {
            Y.one('#unitGridForm').submit();
        });
    }
}

M.block_bcgt.initmygrid = function(Y) {

}

M.block_bcgt.initgridclass = function(Y) {

}

M.block_bcgt.initactivities = function(Y) {

}

M.block_bcgt.addactivities = function(Y) {

}

M.block_bcgt.inittargetqualsettings = function(Y){
      $(".contentCollapse").hide();
	  //toggle the componenet with class msg_body
	  $(".headingCollapse").click(function()
	  {
        $(this).next(".contentCollapse").slideToggle(500);
	  });
      
      $(".delete").click(function(){
          $(this).preventDefault();
      })
}

M.block_bcgt.inittransferunits = function(Y){
    
    var hover = Y.all('.TRANSFERHOVER');
    if (hover != null){
        
        hover.each( function(e){
            
            e.on('mouseover', function(j){

                // FInd out what unit it is
                var unitID = e.getAttribute('unitid');
                if (unitID != null)
                {
                    if (Y.one('.UNIT'+unitID).hasClass('marked') == false){
                        Y.one('.UNIT'+unitID).setStyle('backgroundColor', 'orange');
                    }
                }

            });
            
            e.on('mouseout', function(j){

                // FInd out what unit it is
                var unitID = e.getAttribute('unitid');
                if (unitID != null)
                {
                    if (Y.one('.UNIT'+unitID).hasClass('marked') == false){
                        Y.one('.UNIT'+unitID).setStyle('backgroundColor', 'inherit');
                    }
                }

            });
            
            
        } );
        
        
    }
    
    var checkbox = Y.all('.transferUnit');
    checkbox.each( function(e){
        
        e.on('click', function(j){
        
            var checked = e.get('checked');
        
            // FInd out what unit it is
            var unitID = e.getAttribute('unitid');
            if (unitID != null)
            {
                if (checked)
                {
                    Y.all('.FROMUNIT'+unitID).setStyle('backgroundColor', 'lime');
                    Y.all('.UNIT'+unitID).setStyle('backgroundColor', 'red');
                    Y.all('.UNIT'+unitID).addClass('marked');
                }
                else
                {
                    Y.all('.FROMUNIT'+unitID).setStyle('backgroundColor', 'inherit');
                    Y.all('.UNIT'+unitID).setStyle('backgroundColor', 'inherit');
                    Y.all('.UNIT'+unitID).removeClass('marked');
                }
            }
        
        });
        
        
        
    } );
    
    
}

