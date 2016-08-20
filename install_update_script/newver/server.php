<?php 
    include "versionlist.php";

    $data       = json_decode(base64_decode(str_rot13($_GET['data'])), TRUE);

    $version    = trim(strip_tags($data['version']));
    $language   = trim(strip_tags($data['lang']));
    
    include 'lang.php';
    
    if(!$data["auto"]) {
        include 'manual.php';
    }
    
    $updateData             = array();
    $updateData['version']  = $newversion;
    
    $updateData['filepath'] = $versions[$version]['file'];
    
    $updateData['force']    = $versions[$version]['force'];
    
    $updateData['message']  = isset($versions[$version]['message'])
                            ? $versions[$version]['message']
                            : false;
     
    $updateData['notice']  = isset($versions[$version]['notice'])
                            ? $versions[$version]['notice']
                            : false;   
    
    header('Content-type: text/plain');
    header("Content-Transfer-Encoding: binary\n");
    print str_rot13(base64_encode(json_encode($updateData)));
    flush();    
    
?>
