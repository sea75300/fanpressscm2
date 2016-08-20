<?php
    /**
     * Logout script
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     */

    define('NO_HEADER','');  
    require_once dirname(__FILE__).'/inc/acpcommon.php';
    
    if(isset($_COOKIE["fpsessionid"])) {
        $fpSystem->UsrLogout();
        
        if(isset($_GET['redirect'])) {
            sleep(1);
            header("location: http://", trim(strip_tags(urldecode($_GET['redirect']))));
        } else {
            header("location: login.php");
        }
    } else {
        header("location: index.php");
    }
?>