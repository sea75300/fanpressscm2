<?php
    switch($language) {
        case "de" :
            $lang["current"] = "Deine FanPress-Version ist <b>aktuell</b>!";
            $lang["old"]     = "Deine FanPress-Version ist <b>veraltet</b>!";
            $lang["dev"]     = "Die Version die du verwendest ist eine <b>Test- oder Entwickler-Version</b>.";
            $lang["info"]    = "Release Infos";
            $lang["download"]= "Update herunterladen";
        break;
        default :
            $lang["current"] = "Your FanPress-Version is <b>up to date</b>!";
            $lang["old"]     = "Your FanPress-Version is <b>outdated</b>!";
            $lang["dev"]     = "You are running a <b>test- or developer version</b>.";
            $lang["info"]    = "Release info";
            $lang["download"]= "Download update";
        break;
    }    