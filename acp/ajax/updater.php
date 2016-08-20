<?php
    /**
     * FanPress CM Auto Updater ajax processing
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
	
    header("Content-Type: text/html; charset=iso-8859-1");

    define('NO_HEADER','');
    require_once dirname(dirname(dirname(__FILE__))).'/inc/acpcommon.php';
    
    if(!$fpSystem->canConnect()) { header("Location: index.php"); }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system") && isset($_GET["newver"]) && isset($_GET['step'])) {
        
        $versions = json_decode(base64_decode(fpSecurity::fpfilter(array('filterstring' => $_GET["newver"]), array(7))), true);

        if(isset($_GET['versionfile']) && defined("FPCM_SKIPVERSIONS")) {
            $versions['versionfile'] = fpSecurity::fpfilter(array('filterstring' => $_GET['versionfile']), array(1,4,7));
        }
        
        if(!isset($versions['versionfile']) || !isset($versions['newversion'])) {
            die('e000a');
        }        
        
        if(version_compare($versions['newversion'],FPSYSVERSION,"<=") && $_GET['step'] < 2 && !defined("FPCM_SKIPVERSIONS")) {
            fpMessages::showSysNotice(LANG_AUTO_UPDATE_UPTODATE_MSG);
        } else {            
            $update_folder = FPUPGRADEFOLDER;

            if(!is_writable($update_folder)) {	
                fpMessages::writeToSysLog("auto updater error no permissions");
                die('e000b');
            } else {

                $start_time = time();

                define("UPDATE_FILE_REMOTE_FILE", basename($versions['versionfile']));
                define("UPDATE_FILE_REMOTE_NAME", $versions['versionfile']);
                
                // Update-Packet herunterladen
                if($_GET['step'] == 1) {
                    if($fpFileSystem->downloadPackage($update_folder, UPDATE_FILE_REMOTE_FILE, UPDATE_FILE_REMOTE_NAME)) {
                        fpMessages::showSysNotice(LANG_AUTO_UPDATE_DLSUCCESS_MSG);
                    } else {
                        fpMessages::writeToSysLog("auto updater error file download failed");
                        die('e001');
                    }                    
                }
                
                // Update-Packet entpacken und vorhandene Dateien überschreiben
                if($_GET['step'] == 2) {                    
                    if(file_exists($update_folder.UPDATE_FILE_REMOTE_FILE)) {
                        fpMessages::showSysNotice(LANG_AUTO_UPDATE_UNZIPCOPY);
                    
                        $fpFileSystem = new fpFileSystem($fpDBcon);
                        if($fpFileSystem->updaterUnzipAndCopy($update_folder.UPDATE_FILE_REMOTE_FILE, $update_folder, false, false)) {
                            die('s002');
                        } else {
                            die('e002');
                        }
                    }
                }
                
                // Datenbank aktualisieren
                if($_GET['step'] == 3 && file_exists($update_folder.UPDATE_FILE_REMOTE_FILE)) {
                    if(file_exists(FPBASEDIR."/update/update.php") && file_exists(FPBASEDIR."/update/c_fpupdate.php") && file_exists(FPBASEDIR."/update/ulang_".fpConfig::get('system_lang').".php")) {									
                        require_once(FPBASEDIR."/update/ulang_".fpConfig::get('system_lang').".php");								

                        $fpUpdate = new fpUpdate();
                        $fpUpdate->runUpdate();
                    } else {
                        fpMessages::writeToSysLog("auto updater error file copy failed");
                        die('e003');
                    }
                }
                
                // Temporäre Dateien löschen
                if($_GET['step'] == 4 && file_exists($update_folder.UPDATE_FILE_REMOTE_FILE)) {
                    unlink($update_folder.UPDATE_FILE_REMOTE_FILE);
                    $fpFileSystem->deleteRecursive($update_folder."fanpress");
                    $fpFileSystem->deleteRecursive(FPBASEDIR."/update");
                    
                    die('m004');
                }                
                
                // Temporäre Dateien löschen
                if($_GET['step'] == 5) {
                    fpMessages::showSysNotice(LANG_AUTO_UPDATE_SUCCESS_MSG);
                }
            }            
        }
        
        die();
        
    }
?>