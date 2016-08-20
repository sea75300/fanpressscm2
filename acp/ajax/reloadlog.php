<?php
    /**
     * System-Logs neu laden
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2014
     *
     */
    header("Content-Type: text/html; charset=iso-8859-1");

    define('NO_HEADER','');
    require_once dirname(dirname(__DIR__)).'/inc/acpcommon.php';
    
    $syslogs = new fpLogs();
    
    switch ((int) $_POST['reload']) {
        case 0 :
            $syslogs->getUserLog();
        break;
        case 1 :
            $syslogs->getSysLog();
        break;
        case 2 :
            $syslogs->getPhpLog();
        break;
        case 3 :
            $syslogs->getSqlLog();
        break;
    }
    
    die();