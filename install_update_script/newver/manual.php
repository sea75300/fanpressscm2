<?php

    $bodyStyle  = "<style>body{font-family:'Droid Sans',sans-serif!important;font-size:12pt;text-align:left;background:#fff;margin:0;padding:0;height:100%;}p{padding: 0.8em;margin:0;}a{color:#000;}a.update{padding:0.3em;border:1px solid #C5DBEC;background:#DFEFFC;font-weight:700;color:#2E6E9E;border-radius:5px;-webkit-border-radius:5px;-o-border-radius:5px;}</style>";
    $stdStyle   = "text-align:center;padding:0.4em;margin: 0.4em 0;border-radius: 5px;-webkit-border-radius: 5px;-o-border-radius: 5px;";

    $filepath = (version_compare($version, $newversion, '<'))
              ? $versions[$version]['file']
              : null;

    if(is_null($filepath)) {
        if(version_compare($version, $newversion, '=')) {
            $msg    = array();
            $msg[]  = "$bodyStyle <div style=\"$stdStyle background:#CCE9F4;border: 1px solid #69BDDD;\">";
            $msg[]  = $lang['current'];
            $msg[]  = "<small>";
            if(isset($versions[$version]['message'])) $msg[]  = "<br>".$versions[$version]['message']." &bull; ";
            $msg[]  = "<a target=\"_blank\" href=\"".(isset($versions[$version]['notice']) ? $versions[$version]['notice'] : '#')."\">".$lang['info']."</a>";
            $msg[]  = "</small>";
            $msg[]  = "</div>";

            die(implode(PHP_EOL, $msg));
        }

        die("$bodyStyle <div style=\"$stdStyle background:#FEFFDB;border: 1px solid #A07FFE;\">".$lang['dev']."</div>");
    }

    if(!$versions[$version]['force']) {
        $msg    = array();
        $msg[]  = "$bodyStyle <div style=\"$stdStyle background:#E6A8A8;border: 1px solid #cc0000;\">";
        $msg[]  = $lang['old'];
        $msg[]  = "<p><a target=\"_blank\" class=\"update\" href=\"".$versions[$version]['file']."\">".$lang["download"]."</a></p>";
        $msg[]  = "<small>";
        if(isset($versions[$version]['message'])) $msg[]  = $versions[$version]['message']." &bull; ";
        if(isset($versions[$version]['notice'])) $msg[]   = "<a target=\"_blank\" href=\"".$versions[$version]['notice']."\">".$lang['info']."</a>";
        $msg[]  = "</small>";
        $msg[]  = "</div>";

        die(implode(PHP_EOL, $msg));
    }

    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".basename($filepath));
    header("Content-Length:".filesize($datei));
    readfile($filepath);       

    die();