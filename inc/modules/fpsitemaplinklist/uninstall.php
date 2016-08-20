<?php
    /**
     * FanPress CM Sitemap Liste module
     * @author Stefan Seehafer (fanpress@nobody-knows.org)
     * @copyright 2014
     *
     */

    if(!defined('FPBASEDIR')) define ('FPBASEDIR', dirname(dirname(dirname(dirname(__FILE__)))));
    
    if(!defined('MOD_SITEMAPLIST_KEY')) define('MOD_SITEMAPLIST_KEY', basename(__DIR__));

    if(!isset($_GET["loadmodule"])) { include_once FPBASEDIR.'/inc/acpcommon.php'; }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) {
        fpMessages::writeToSysLog('Remove config option in database...');
        
        fpModules::removeModuleConfigOption(MOD_SITEMAPLIST_KEY, 'xmlfilepath');
        
        if(file_exists(__DIR__.'/activelist.php')) {
            unlink(__DIR__.'/activelist.php');
        }
    }
?>