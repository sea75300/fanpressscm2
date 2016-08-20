<?php
    /**
     * FanPress CM Module Example 
     * @author Stefan Seehafer (fanpress@nobody-knows.org)
     * @copyright 2012
     *
     */

    /*
     * This is just a dummy file. The uninstall.php is requiered if you want to remove tables in
     * database, files, etc. on module uninstallation.
     */
    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) {
        fpModules::removeModuleConfigOption("fpmoduleexample", "dummy_value");
        
        if(defined('FPCM_MODULE_NOAUTO_UNINSTALL') && FPCM_MODULE_NOAUTO_UNINSTALL) {
            fpMessages::showSysNotice(LANG_MODULE_UNINSTALLED);
        }
    }
?>
