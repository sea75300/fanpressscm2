<?php
    // update server
    define("FPUPDATESERVER", "nobody-knows.org");
    
    // update server check scripts
    define("FPUPDATESCRIPT", "http://".FPUPDATESERVER."/updatepools/fanpress/system/server.php");   
    define("FPMODUPDATESCRIPT", "http://".FPUPDATESERVER."/updatepools/fanpress/modules/server.php");
    define("FPMODUPDATEINSTALL", "http://".FPUPDATESERVER."/updatepools/fanpress/modules/packages/");
       
    // special chars time fix
    define('FPSPECIALCHARTIMEFIX', 1330992033);
    
    // data folder
    define('FPDATAFOLDER', FPBASEDIR."/data");
    
    // filemanager thumbs folder    
    define('FPFMGRTHUMBS', FPDATAFOLDER."/fmgrthumbs");
    
    // cache folder
    $fpcachefolder  = FPDATAFOLDER."/cache";
    
    // upload folder
    $fpuploadfolder = "/data/upload/";
    
    // password reg ex
    $fppasswdregex  = "/^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/";
    
    // datetime masks in autocomplete
    $fpoptiondtmasksac = array("d.m.Y, H:i", "d. M Y, H:i", 'd.n.Y H:i', 'j. M Y H:i', 'j.n.Y H:i', "M dS Y - h:ia", "m/d/Y - h:ia", 'M jS Y - h:ia', 'n/d/Y - h:ia');
    
    if(!defined('FPCM_INSTALL')) {
        // URL zu FanPress CM ACP
        define('FPBASEURL', '//'.$_SERVER['HTTP_HOST'].FP_ROOT_DIR);
        
        // URL zu FanPress CM ACP
        define('FPBASEURLACP', '//'.$_SERVER['HTTP_HOST'].FP_ROOT_DIR.'acp/');
    }      
