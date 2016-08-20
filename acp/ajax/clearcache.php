<?php
 /**
  * ACP Cache clear
  * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
  * @copyright 2011-2014
  *
  */
    define('NO_HEADER','');
    require_once dirname(dirname(__DIR__)).'/inc/acpcommon.php';

    if(LOGGED_IN_USER) {
        $fpCache->cleanup();
    } else {
        if(!fpSecurity::checkPermissions("editnews")) { fpMessages::showNoAccess(); }
        if(!LOGGED_IN_USER) { fpMessages::showNoAccess(LANG_ERROR_NOACCESS); }
    }  
?>