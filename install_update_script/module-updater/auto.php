<?php

    header('Content-type: text/plain');
    header("Content-Transfer-Encoding: binary\n");
    print str_rot13(base64_encode(json_encode($modulesServer)));
    flush(); 