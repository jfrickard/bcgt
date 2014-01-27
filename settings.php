<?php
//General
$settings->add(new admin_setting_configcheckbox(
        'bcgt/linkqualcourse',
        get_string('labellinkqualcourse', 'block_bcgt'),
        get_string('desclinkqualcourse', 'block_bcgt'),
        '1'
        ));

$settings->add(new admin_setting_configcheckbox(
        'bcgt/linkqualstudent',
        get_string('labellinkqualstudent', 'block_bcgt'),
        get_string('desclinkqualstudent', 'block_bcgt'),
        '1'
        ));

$settings->add(new admin_setting_configcheckbox(
        'bcgt/linkqualteacher',
        get_string('labellinkqualteacher', 'block_bcgt'),
        get_string('desclinkqualteacher', 'block_bcgt'),
        '1'
        ));

// Do we want to use the enrol & unenrol events to automatically link users to quals if they enrol/unenrol on a course with a qual?
$settings->add(
        new admin_setting_configcheckbox('bcgt/autoenrolusers', get_string('autoenrolusers', 'block_bcgt'), get_string('autoenrolusersdesc', 'block_bcgt'), '1')
);

$settings->add(
        new admin_setting_configcheckbox('bcgt/autounenrolusers', get_string('autounenrolusers', 'block_bcgt'), get_string('autounenrolusersdesc', 'block_bcgt'), '0')
);

$settings->add(
        new admin_setting_configcheckbox('bcgt/autocalculateasptargetgrade', get_string('labelautocalculateasptargetgrade', 'block_bcgt'), get_string('descautocalculateasptargetgrade', 'block_bcgt'), '0')
);

$settings->add(
        new admin_setting_configtext('bcgt/autocalcaspvalue', get_string('labelautocalcaspvalue', 'block_bcgt'), get_string('descautocalcaspvalue', 'block_bcgt'), '1')
);

$settings->add(
        new admin_setting_configcheckbox('bcgt/showtargetgrades', get_string('labelshowtargetgrades', 'block_bcgt'), get_string('descshowtargetgrades', 'block_bcgt'), '1')
);

$settings->add(
        new admin_setting_configcheckbox('bcgt/showaspgrades', get_string('labelshowaspgrades', 'block_bcgt'), get_string('descshowaspgrades', 'block_bcgt'), '0')
);

//Theming
$settings->add(new admin_setting_configcheckbox(
        'bcgt/themejquery',
        get_string('labelthemejquery', 'block_bcgt'),
        get_string('descthemejquery', 'block_bcgt'),
        '0'
        ));

$settings->add(new admin_setting_configtext(
        'bcgt/themejqueryloc',
        get_string('labelthemejqueryloc', 'block_bcgt'),
        get_string('descthemejqueryloc', 'block_bcgt'),
        ''
        ));

//grids and orders
$settings->add(new admin_setting_configtext(
        'bcgt/pagingnumber',
        get_string('labelpagingnumber', 'block_bcgt'),
        get_string('descpagingnumber', 'block_bcgt'),
        '20'
        ));

//ALEVELS
$settings->add(new admin_setting_configcheckbox(
        'bcgt/alevelusefa',
        get_string('labelalevelusefa', 'block_bcgt'),
        get_string('descalevelusefa', 'block_bcgt'),
        '0'
        ));

$settings->add(new admin_setting_configcheckbox(
        'bcgt/alevelManageFACentrally',
        get_string('labelalevelManageFACentrally', 'block_bcgt'),
        get_string('descalevelManageFACentrally', 'block_bcgt'),
        '0'
        ));

$settings->add(new admin_setting_configcheckbox(
        'bcgt/alevelLinkAlevelGradeBook',
        get_string('labelalavelLinkAlevelGradeBook', 'block_bcgt'),
        get_string('descalavelLinkAlevelGradeBook', 'block_bcgt'),
        '0'
        ));

$settings->add(new admin_setting_configcheckbox(
    'bcgt/alevelgradebookscaleonly',
    get_string('labelalevelgradebookscaleonly', 'block_bcgt'),
    get_string('descalevelgradebookscaleonly', 'block_bcgt'),
    '1'
    ));

$settings->add(new admin_setting_configcheckbox(
        'bcgt/aleveluseceta',
        get_string('labelaleveluseceta', 'block_bcgt'),
        get_string('descaleveluseceta', 'block_bcgt'),
        '0'
        ));

//$settings->add(new admin_setting_configcheckbox(
//        'bcgt/alevelusecalcpredicted',
//        get_string('labelalevelusecalcpredicted', 'block_bcgt'),
//        get_string('descalevelusecalcpredicted', 'block_bcgt'),
//        '0'
//        ));

//$settings->add(new admin_setting_configcheckbox(
//        'bcgt/alevelproggradefa',
//        get_string('labelalevelpgfa', 'block_bcgt'),
//        get_string('descalevelpgfa', 'block_bcgt'),
//        '0'
//        ));

//$settings->add(new admin_setting_configcheckbox(
//        'bcgt/alevelproggradehw',
//        get_string('labelalevelpggb', 'block_bcgt'),
//        get_string('descalevelpggb', 'block_bcgt'),
//        '0'
//        ));

$settings->add(new admin_setting_configcheckbox(
        'bcgt/alevelallowalpsweighting',
        get_string('labelalevelallowalpsweighting', 'block_bcgt'),
        get_string('descalevelallowalpsweighting', 'block_bcgt'),
        '0'
        ));

$settings->add(new admin_setting_configtext(
        'bcgt/weightedtargetmethod',
        get_string('labelweightedtargetmethod', 'block_bcgt'),
        get_string('descweightedtargetmethod', 'block_bcgt'),
        '2'
        ));

$settings->add(new admin_setting_configtext(
        'bcgt/aleveldefaultalpsperc',
        get_string('labelaleveldefaultalpspercentage', 'block_bcgt'),
        get_string('descaleveldefaultalpspercentage', 'block_bcgt'),
        '75'
        ));

//BTECS
$settings->add(new admin_setting_configtext(
        'bcgt/btecunitspredgrade',
        get_string('labelbtecunitspredgrade', 'block_bcgt'),
        get_string('descbtecunitspredgrade', 'block_bcgt'),
        '3'
        ));

$settings->add(new admin_setting_configtext(
        'bcgt/btecgridcolumns',
        get_string('labelbtecgridcolumns', 'block_bcgt'),
        get_string('descbtecgridcolumns', 'block_bcgt'),
        'picture,username,name'
        ));

$settings->add(new admin_setting_configtext(
        'bcgt/bteclockedcolumnswidth',
        get_string('labelbteclockedcolumnswidth', 'block_bcgt'),
        get_string('descbteclockedcolumnswidth', 'block_bcgt'),
        '430'
        ));          

$settings->add(new admin_setting_configtext(
        'bcgt/logoimgurl',
        get_string('logoimgurl', 'block_bcgt'),
        get_string('desclogoimgurl', 'block_bcgt'),
        $CFG->wwwroot . '/blocks/bcgt/pix/bc.png'
        ));  

$settings->add(new admin_setting_configcheckbox(
        'bcgt/showcoursecategories',
        get_string('labelshowcoursecategories', 'block_bcgt'),
        get_string('descshowcoursecategories', 'block_bcgt'),
        '0'
        ));


//$settings->add(new admin_setting_configcheckbox(
//        'bcgt/enrolstudentqual',
//        get_string('labelenrolstudentqual', 'block_bcgt'),
//        get_string('descenrolstudentqual', 'block_bcgt'),
//        '0'
//        ));
//
//$settings->add(new admin_setting_configcheckbox(
//        'bcgt/unenrolstudentqual',
//        get_string('labelunenrolstudentqual', 'block_bcgt'),
//        get_string('descunenrolstudentqual', 'block_bcgt'),
//        '0'
//        ));
//
//$settings->add(new admin_setting_configcheckbox(
//        'bcgt/enroldeaultallunits',
//        get_string('labelenroldeaultallunits', 'block_bcgt'),
//        get_string('descenroldeaultallunits', 'block_bcgt'),
//        '0'
//        ));

    

?>
