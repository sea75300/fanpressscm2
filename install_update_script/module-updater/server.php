<?php 
    include "modulelist.php";

    $data       = json_decode(base64_decode(str_rot13($_GET['data'])), TRUE);

    $version    = trim(strip_tags($data['version']));
    $language   = trim(strip_tags($data['lang']));
    
    include 'lang.php';    

    if(!$data["auto"] && !$data['checkonly']) {
        include 'manual.php';
        die();
    }
    
    if(!$data["auto"] && $data['checkonly']) {
        include 'manual.php';
        die();
    } 
    
    if($data["auto"]) {
        include 'auto.php';
        die();
    }    
    
?>
