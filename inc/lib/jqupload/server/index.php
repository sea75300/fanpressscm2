<?php
    define('NO_HEADER','');
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/inc/acpcommon.php';
    if(!LOGGED_IN_USER) die();

    error_reporting(0);
    require(dirname(__FILE__).'/UploadHandler.php');    
    $upload_handler = new UploadHandler();
    
?>