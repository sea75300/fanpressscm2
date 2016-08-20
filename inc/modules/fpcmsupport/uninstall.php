<?php
    /**
     * FanPress CM Support Module
     * @author Stefan Seehafer (fanpress@nobody-knows.org)
     * @copyright 2014
     *
     */

    if(!defined('FPBASEDIR')) define ('FPBASEDIR', dirname(dirname(dirname(dirname(__FILE__)))));

    if(isset($_GET["ext"])) { define('NOHEADER', ''); }
    include_once FPBASEDIR.'/inc/acpcommon.php';

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) {
        if(!isset($fpDBcon)) { $fpDBcon = new fpDB(); }
        if(!isset($fpUser)) { $fpUser = new fpUser($fpDBcon); }
        if(!isset($fpFileSystem)) { $fpFileSystem = new fpFileSystem($fpDBcon); }          

        if(!isset($fpUser->getUserByUsrName('support')->id)) {
            return;
        }
        
        $fpAuthor = new fpAuthor($fpUser->getUserByUsrName('support')->id);        
        $fpAuthor->delete();
        
        if(isset($fpUser->getUserByUsrName('support')->id)) {
            fpMessages::writeToSysLog("Unable to remove support user!");
        }
        
        if(!isset($_GET['ext'])) {            
            $fpFileSystem->deleteRecursive(dirname(__FILE__));
            
            $fpCache = new fpCache();
            $fpCache->cleanup(0);
            
            fpMessages::writeToSysLog("uninstall module fpcm_support");
        }
    }
?>