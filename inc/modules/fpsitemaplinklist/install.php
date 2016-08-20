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
        fpMessages::showSysNotice('Create config option in database...');
        fpMessages::writeToSysLog('Create config option in database...');
        
        fpModules::addModuleConfigOption(
            MOD_SITEMAPLIST_KEY,
            array(
                'config_name' => 'xmlfilepath',
                'config_value' => dirname(FPBASEDIR).'/sitemap.xml'
            )
        );
    }
?>