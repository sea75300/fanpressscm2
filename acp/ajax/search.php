<?php
    /**
     * Suche
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */
    header("Content-Type: text/html; charset=iso-8859-1");

    define('NO_HEADER','');
    require_once dirname(dirname(__DIR__)).'/inc/acpcommon.php';

    $fupl = array();
    
    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("editnews")) {        

        $maxcmt  = 0;
        $rowNews = $fpNews->searchOrFilterNews(
            fpSecurity::Filter2($_POST["search"]),
            fpSecurity::Filter2($_POST["authors"]),
            fpSecurity::Filter2($_POST["categories"]),
            fpSecurity::Filter2($_POST["type"]),
            fpSecurity::Filter2($_POST["searchfn"]),
            fpSecurity::Filter2($_POST["datetimefrom"]),
            fpSecurity::Filter2($_POST["datetimeto"])
        );        

        if($_POST["searchfn"] == 2) { $showArchived = true; }
        if($_POST["searchfn"] == 1) { $showActive = true; }
        
        include FPBASEDIR.'/acp/newslist/newslist.php';
        
    } else {
        if(!fpSecurity::checkPermissions("editnews")) { fpMessages::showNoAccess(); }
        if(!LOGGED_IN_USER) { fpMessages::showNoAccess(LANG_ERROR_NOACCESS); }
    }  
?>