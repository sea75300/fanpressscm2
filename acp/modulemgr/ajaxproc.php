<?php
    /**
     * FanPress CM Module-Manager AJAX-Controller
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2012-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    define('NO_HEADER','');
    require_once dirname(dirname(__DIR__)).'/inc/acpcommon.php';

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("modules") && isset($_GET['ext']) && isset($_GET['act'])) {
        $moduleKey = fpSecurity::fpfilter(array('filterstring' => $_GET["ext"]));

        switch($_GET['act']) {
            case "enable" :
                fpModules::enableFPModule($moduleKey);
                $messageUrl = 'modules.php?modmsg=enabled';
            break;
            case "disable" :
                fpModules::disableFPModule($moduleKey);
                $messageUrl = 'modules.php?modmsg=disabled';
            break;
            case "uninstall" :
                fpModules::uninstallFPModule($moduleKey, $fpSystem->canConnect());
                $messageUrl = 'modules.php?modmsg=deleted';
            break;                   
        }
    }    
    
    die($messageUrl);