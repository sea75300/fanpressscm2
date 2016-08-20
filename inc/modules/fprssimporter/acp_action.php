<?php
    function fprssimporter_acpRun() {
        if(isset($_POST["btn_importstart"])) {
            $rssImporter = new fpModule_fpRssImporter_RssImporter(fpSecurity::Filter8($_POST["rssfeedurl"]), fpModules::getDBConnection());
            $rssImporter->startImport();
        } else {
            $fpSystem = new fpSystem(fpModules::getDBConnection());
            if(!defined('FPSYSVERSION')) { define('FPSYSVERSION', fpConfig::get('system_version')); }
            
            fpMessages::showErrorText(LANG_MOD_RSSIMPORTER_NOTICE);
            
            fpModules::includeModuleTemplate(fpModule_fpRssImporter_RssImporter::getModulKey(), "importer");
        }
    }

     function fprssimporter_addToNavigation() {
        return array(
            'descr' => LANG_MOD_RSSIMPORTER_NAV,
            'link'  => fpModules::getModuleStdLink(fpModule_fpRssImporter_RssImporter::getModulKey(), '', true)
        );         
     }      
?>