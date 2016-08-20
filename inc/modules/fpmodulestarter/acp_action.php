<?php

    function fpmodulestarter_addToNavigationMain() {   
        return array(
            array(
                'icon'  => 'ui-icon ui-icon-circle-plus',
                'descr' => fpLanguage::returnLanguageConstant(LANG_MOD_MODULESTARTER_NAVIGATION),
                'link'  => fpModules::getModuleStdLink("fpmodulestarter", '', true)
            )
        );
    }     
    
    function fpmodulestarter_addToHeaderJs() {
        return fpModules::getModuleRelPath("fpmodulestarter")."/modulestarterscript.js";
    }
    
    function fpmodulestarter_addToHeaderCss() {
        return fpModules::getModuleRelPath("fpmodulestarter")."/modulestarterstyle.css";
    }    

    function fpmodulestarter_acpRun() {               
        if(fpSecurity::checkPermissions ("system"))  {                        
            $creationFailed = false;

            if(isset($_POST["btnModStarterCreate"])) {
                if(!empty($_POST["modstarterModKey"]) && !empty($_POST["modstarterModName"]) && !empty($_POST["modstarterModType"])) {
                    $fpModuleStarterObj = new fpModule_fpModuleStarter_ModStarter(fpSecurity::Filter5($_POST["modstarterModKey"]), fpModules::getDBConnection());
                    
                    $createFolterReturn = $fpModuleStarterObj->createModuleFolders();
                    
                    if($createFolterReturn) {                        
                        if($fpModuleStarterObj->createModuleConfigFile()) {
                            if(!$creationFailed) {
                                if(!$fpModuleStarterObj->createInstallPHP()) {
                                    $creationFailed = true;
                                }
                                
                                if(!$fpModuleStarterObj->createUninstallPHP()) {
                                    $creationFailed = true;
                                }                                
                            }       
                            
                            if(in_array("fe", $_POST["modstarterModType"]) && !$creationFailed) {
                                if(!$fpModuleStarterObj->createModuleFEAction()) {
                                    $creationFailed = true;
                                }
                            }

                            if(in_array("acp", $_POST["modstarterModType"]) && !$creationFailed) {
                                if(!$fpModuleStarterObj->createModuleACPAction()) {
                                    $creationFailed = true;
                                }
                            }                              
                        }                       
                    } else {
                        if($createFolterReturn == -1) {
                            fpMessages::showSysNotice(LANG_MOD_MODULESTARTER_MODEXIST);
                        } else {
                            $creationFailed = true;
                        }
                    }
                } else {
                    $creationFailed = true;
                }

                if($creationFailed) {
                    fpMessages::showErrorText(LANG_MOD_MODULESTARTER_MODNOTCREATED);
                } else {
                    fpMessages::showSysNotice(str_replace("%modpath%", "/inc/modules/".fpSecurity::Filter5($_POST["modstarterModKey"])."/", LANG_MOD_MODULESTARTER_MODCREATED));
                }             
            }

            $fpSystem = new fpSystem(fpModules::getDBConnection());            
            if(!defined('FPSYSVERSION')) { define('FPSYSVERSION', fpConfig::get('system_version')); }
            
            if(FPSYSVERSION < "2.0.2") {
                include fpModules::getModuleRootPath("fpmodulestarter")."/templates/moduleStarter.php";
            } else {
                fpModules::includeModuleTemplate("fpmodulestarter", "moduleStarter");                
            }            
        }
    }
?>