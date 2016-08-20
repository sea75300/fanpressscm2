<?php
    /**
     * FanPress CM Module Example 
     * @author Stefan Seehafer (fanpress@nobody-knows.org)
     * @copyright 2012
     *
     */

    /**
     * This is just a dummy file. The install.php is requiered if you want to create tables in
     * database, files, etc. on module installation.
     */

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) {
        $configOption = array (
            "config_name" => "dummy_value",
            "config_value" => time(),
        );
        fpModules::addModuleConfigOption("fpmoduleexample", $configOption);
        
        if(defined('FPCM_MODULE_NOAUTO_INSTALL') && FPCM_MODULE_NOAUTO_INSTALL) {
            fpMessages::showSysNotice(LANG_MODULE_INSTALLED);
        }
    }
?>
